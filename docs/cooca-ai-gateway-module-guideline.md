# Cooca AI Gateway — Design & Implementation Guideline
### Modul AI dengan Token Tracking, Quota Control, dan API Key Management

| | |
|---|---|
| **Project** | Cooca.id — Modul baru: AI Gateway (dijual sebagai add-on ke Bagema, Villa & Restaurant, Wavva, dan produk Cooca lainnya) |
| **Stack** | Laravel 13.15 · PHP 8.3 — mengikuti konvensi codebase existing (UUID PK, `declare(strict_types=1)`, `final class`, Service/Repository/Policy layer) |
| **Target Environment** | Shared hosting cPanel (mengikuti batasan yang sudah dibahas di dokumen remediation sebelumnya — tanpa Redis/daemon) |
| **Terintegrasi dengan** | Skema existing: `Product`, `SubscriptionPlan`, `License`, `Subscription`, `Customer`, `Domain` |

---

## ⚠️ Penting: Dua Jenis "Token" yang Jangan Tertukar

Codebase kamu sudah punya kolom `token_code` di tabel `licenses` — itu adalah **token validasi lisensi** (dipakai `LicenseValidationController` untuk cek apakah instalasi ERP sah). Dokumen ini membahas **AI token** — satuan pemakaian model AI (mirip "kata"/"suku kata" yang dihitung OpenAI/Anthropic per request, dipakai untuk mengukur biaya & kuota pemakaian). **Dua hal yang sama sekali berbeda, sengaja dipisah rapat di desain ini** supaya tidak tercampur secara konsep maupun di kode (lihat §2 kenapa dibuat tabel `ai_api_keys` terpisah, bukan menumpangi `token_code` yang sudah ada).

---

## 1. Ringkasan Konsep

Cooca AI Gateway adalah **proxy internal** antara produk-produk Cooca (Bagema, Villa & Restaurant, Wavva, dst) dengan provider AI (OpenAI, Anthropic, dll). Alih-alih tiap produk simpan API key OpenAI/Anthropic sendiri-sendiri (mahal untuk dikontrol, gampang disalahgunakan, sulit dijual sebagai fitur berbayar), semua request AI dari produk-produk itu **lewat satu pintu**: gateway milik Cooca.

```
┌──────────────┐        ┌─────────────────────┐        ┌──────────────┐
│  Bagema ERP  │───┐    │                     │        │   OpenAI     │
│  (tenant A)  │   │    │   Cooca AI Gateway   │───────▶│  Anthropic   │
├──────────────┤   ├───▶│  (api.cooca.id/v1)   │        │  (provider   │
│  Villa ERP   │   │    │                     │        │   asli)      │
│  (tenant B)  │   │    │  - Auth API Key      │◀───────│              │
├──────────────┤   │    │  - Cek kuota         │        └──────────────┘
│  Wavva Booking│───┘    │  - Rate limit        │
│  (tenant C)  │        │  - Catat usage       │
└──────────────┘        │  - Forward ke provider│
                         └─────────────────────┘
```

**Kenapa arsitektur ini, bukan tenant connect langsung ke OpenAI:**
1. **Kontrol biaya** — kamu yang pegang API key asli provider, bukan tenant. Kalau ada tenant yang salah pakai/disalahgunakan, cukup revoke satu key Cooca, bukan minta OpenAI rotate key mereka.
2. **Bisa dijual sebagai modul berbayar** — quota jadi basis pricing tier (AI Starter/Growth/Business), reuse mekanisme `SubscriptionPlan`+`License` yang sudah ada, tidak perlu bikin sistem billing baru dari nol.
3. **Ganti provider tanpa ubah kode tenant** — kalau suatu saat pindah dari OpenAI ke provider lain (atau tambah provider kedua untuk model tertentu), cukup ubah konfigurasi di gateway, tenant app tidak perlu update kode sama sekali (karena mereka bicara ke endpoint Cooca, bukan langsung ke OpenAI).
4. **Data pemakaian buat analisis bisnis** — kamu tahu persis produk mana yang paling banyak pakai AI, model apa yang paling laku, margin per customer — semua data untuk keputusan pricing ke depan.

---

## 2. Skema Database

Semua tabel baru pakai `HasUuids` + `declare(strict_types=1)` + `final class`, sama seperti model lain di codebase kamu.

### 2.1 `ai_api_keys` — Kredensial gateway per produk/tenant

```php
// database/migrations/..._create_ai_api_keys_table.php
Schema::create('ai_api_keys', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('license_id');           // AI Module license milik customer ini (lihat §3)
    $table->uuid('customer_id');          // denormalized, konsisten dgn pola License/Subscription
    $table->uuid('domain_id')->nullable(); // instance ERP mana yang pakai key ini (Bagema/Villa/Wavva tenant tertentu)
    $table->string('name');               // label manusiawi, mis. "Bagema Production"
    $table->string('key_prefix', 12)->unique(); // 12 char pertama, untuk lookup cepat tanpa hash-compare semua baris
    $table->string('key_hash', 64);       // SHA-256 hex dari key penuh — TIDAK PERNAH simpan plaintext
    $table->enum('status', ['active', 'revoked'])->default('active');
    $table->timestamp('last_used_at')->nullable();
    $table->timestamp('revoked_at')->nullable();
    $table->timestamps();

    $table->foreign('license_id')->references('id')->on('licenses')->onDelete('cascade');
    $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
    $table->foreign('domain_id')->references('id')->on('domains')->onDelete('set null');

    $table->index(['status', 'key_prefix']); // dipakai di SETIAP request — wajib index
});
```

**Kenapa `key_prefix` + `key_hash` terpisah, bukan cuma hash:** ini pola yang sama dipakai Stripe/GitHub — 12 karakter pertama disimpan plain (bukan rahasia, cuma buat identifikasi visual "key ini yang mana" di dashboard admin/customer dan buat lookup cepat pakai index), sisanya cuma bisa diverifikasi lewat hash. Kalau cuma simpan hash tanpa prefix, setiap kali validasi kamu harus scan semua baris (lambat begitu jumlah key banyak).

### 2.2 `ai_usage_logs` — Log tiap request (append-only, untuk audit & analitik)

```php
Schema::create('ai_usage_logs', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('ai_api_key_id');
    $table->uuid('license_id');           // denormalized — dipakai query agregasi tanpa join
    $table->string('provider', 32);       // "openai" | "anthropic"
    $table->string('model', 64);          // "gpt-4o-mini", "claude-haiku-4-5", dst
    $table->unsignedInteger('prompt_tokens')->default(0);
    $table->unsignedInteger('completion_tokens')->default(0);
    $table->unsignedInteger('total_tokens')->default(0);
    $table->decimal('cost_usd', 10, 6)->nullable(); // biaya asli ke provider, untuk hitung margin
    $table->enum('status', ['success', 'error', 'quota_exceeded', 'rate_limited'])->default('success');
    $table->unsignedSmallInteger('http_status')->nullable();
    $table->unsignedInteger('duration_ms')->nullable();
    $table->timestamp('created_at')->useCurrent(); // append-only, tidak butuh updated_at

    $table->foreign('ai_api_key_id')->references('id')->on('ai_api_keys')->onDelete('cascade');
    $table->foreign('license_id')->references('id')->on('licenses')->onDelete('cascade');

    $table->index(['license_id', 'created_at']); // untuk laporan usage per periode
    $table->index(['ai_api_key_id', 'created_at']);
});
```

> **Privasi:** tabel ini **sengaja tidak menyimpan isi prompt/response** — hanya metadata pemakaian. Kalau nanti butuh log isi percakapan untuk debugging, buat tabel terpisah `ai_usage_log_payloads` yang opt-in per customer (banyak SME sensitif soal data bisnis mereka dikirim ke server pihak ketiga, apalagi disimpan juga di server kamu) dan beri retention period pendek (mis. auto-delete setelah 7 hari).

### 2.3 `ai_usage_cycles` — Counter kuota per periode billing (kunci performa)

Ini bagian paling penting secara desain performa. **Jangan pernah `SUM()` tabel `ai_usage_logs` di setiap request** untuk cek sisa kuota — begitu log-nya jutaan baris, ini jadi query lambat persis seperti masalah `->get()` tanpa pagination yang sudah dibahas di dokumen remediation sebelumnya. Sebagai gantinya, pakai **counter yang di-increment atomik**:

```php
Schema::create('ai_usage_cycles', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('license_id');
    $table->date('cycle_start');
    $table->date('cycle_end');
    $table->unsignedBigInteger('tokens_used')->default(0); // di-increment tiap request sukses
    $table->unsignedBigInteger('token_quota');              // snapshot dari plan SAAT cycle dimulai
    $table->timestamps();

    $table->foreign('license_id')->references('id')->on('licenses')->onDelete('cascade');
    $table->unique(['license_id', 'cycle_start']);
    $table->index(['license_id', 'cycle_end']); // dipakai job reset harian (§7)
});
```

Cek kuota jadi cukup **satu baris terindeks**, bukan agregasi:

```php
$cycle = AiUsageCycle::where('license_id', $license->id)
    ->where('cycle_start', '<=', now())
    ->where('cycle_end', '>=', now())
    ->first();

$remaining = $cycle->token_quota - $cycle->tokens_used;
```

**Kenapa `token_quota` di-snapshot per cycle** (bukan selalu baca dari plan langsung): supaya kalau customer upgrade/downgrade plan di tengah bulan, perubahan kuota berlaku mulai cycle berikutnya — bukan tiba-tiba berubah di tengah periode yang sudah berjalan. Konsisten dengan pola `previous_plan_id`/`prorated_amount` yang sudah ada di tabel `subscriptions` kamu.

### 2.4 `ai_plan_configs` — Konfigurasi AI per `subscription_plan_id`

Alih-alih menambah kolom AI-specific langsung ke tabel `subscription_plans` (yang dipakai juga oleh produk non-AI seperti Bagema/Villa/Wavva — jangan dipolusi), buat tabel konfigurasi terpisah yang hanya relevan untuk plan bertipe AI:

```php
Schema::create('ai_plan_configs', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('subscription_plan_id')->unique();
    $table->unsignedBigInteger('monthly_token_quota');
    $table->unsignedSmallInteger('requests_per_minute')->default(20); // rate limit tier
    $table->json('allowed_models'); // ["gpt-4o-mini","claude-haiku-4-5"] — whitelist model per tier
    $table->enum('overage_policy', ['hard_stop', 'soft_allow'])->default('hard_stop');
    $table->timestamps();

    $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');
});
```

### 2.5 `ai_provider_configs` & `ai_model_pricing` — Konfigurasi internal (admin-only)

```php
Schema::create('ai_provider_configs', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('provider', 32)->unique(); // "openai", "anthropic"
    $table->text('api_key');    // di-cast 'encrypted' di model — kredensial asli provider
    $table->string('base_url');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

Schema::create('ai_model_pricing', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('provider', 32);
    $table->string('model', 64);
    $table->decimal('input_price_per_1k', 10, 6);  // USD per 1000 token input
    $table->decimal('output_price_per_1k', 10, 6); // USD per 1000 token output
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->unique(['provider', 'model']);
});
```

```php
// app/Models/AiProviderConfig.php
final class AiProviderConfig extends Model
{
    use HasUuids;
    protected $table = 'ai_provider_configs';
    protected $fillable = ['provider', 'api_key', 'base_url', 'is_active'];
    protected $casts = [
        'api_key' => 'encrypted', // WAJIB — ini kredensial asli OpenAI/Anthropic
        'is_active' => 'boolean',
    ];
    protected $hidden = ['api_key'];
}
```

---

## 3. Cara "Menjual" Modul Ini — Reuse Billing yang Sudah Ada

Ini keputusan arsitektur paling penting: **jangan bikin sistem langganan baru untuk AI Module.** Perlakukan AI Module sebagai `Product` biasa di katalog Cooca yang sudah ada, lalu semua mekanisme lifecycle (checkout, License, Subscription, expiry, revocation, invoice) otomatis kepakai tanpa kode tambahan.

```
1. Admin bikin Product baru:
   Product::create([
       'product_type' => 'addon',           // sudah ada di const TYPES kamu
       'name' => 'Cooca AI Assistant',
       'slug' => 'ai-assistant',
       ...
   ]);

2. Admin bikin SubscriptionPlan untuk product itu, misalnya:
   - "AI Starter"  → ai_plan_configs.monthly_token_quota = 300.000
   - "AI Growth"   → 1.500.000
   - "AI Business" → 6.000.000

3. Customer beli "AI Starter" lewat flow checkout yang SUDAH ADA
   → Subscription dibuat (status trial/active) seperti biasa
   → License dibuat (license_code, token_code, domain, expires_at) seperti biasa

4. Begitu License untuk AI Module ini "active", baru AiApiKey pertama
   dibuat otomatis (lihat §4) dan AiUsageCycle pertama diinisialisasi.
```

**Keuntungan pendekatan ini:** trial period, prorasi upgrade/downgrade, auto-renew, invoice, dan grace period untuk AI Module **otomatis dapat semua** dari sistem yang sudah teruji — kamu cuma perlu tambah listener yang bereaksi saat status License AI Module berubah.

```php
// app/Listeners/Ai/ProvisionAiApiKeyOnLicenseActivated.php
declare(strict_types=1);

namespace App\Listeners\Ai;

use App\Events\LicenseActivated; // pakai event yang sudah ada kalau ada, atau observer di model License
use App\Models\Product;
use App\Services\Ai\AiApiKeyService;

final class ProvisionAiApiKeyOnLicenseActivated
{
    public function __construct(private readonly AiApiKeyService $keyService) {}

    public function handle(LicenseActivated $event): void
    {
        $license = $event->license;

        // Hanya proses kalau product ini memang AI Module
        $aiProduct = Product::where('slug', 'ai-assistant')->first();
        if (!$aiProduct || $license->product_id !== $aiProduct->id) {
            return;
        }

        $this->keyService->issueForLicense($license, name: 'Default Key');
    }
}
```

Kalau di codebase kamu belum ada event `LicenseActivated`, tambahkan lewat Model Observer sederhana di `License` (cek perubahan `status` jadi `active`) — lebih rapi daripada menaruh logic ini di controller.

---

## 4. Penerbitan API Key

```php
// app/Services/Ai/AiApiKeyService.php
declare(strict_types=1);

namespace App\Services\Ai;

use App\Models\AiApiKey;
use App\Models\License;
use Illuminate\Support\Str;

final class AiApiKeyService
{
    private const PREFIX_LENGTH = 12;

    /**
     * @return array{model: AiApiKey, plain_key: string} plain_key HANYA muncul sekali di sini,
     *         tidak pernah bisa diambil ulang setelah response ini dikirim ke customer.
     */
    public function issueForLicense(License $license, string $name, ?string $domainId = null): array
    {
        $rawKey = 'cooca_ai_live_' . Str::random(40);
        $prefix = Str::substr($rawKey, 0, self::PREFIX_LENGTH);

        $model = AiApiKey::create([
            'license_id'  => $license->id,
            'customer_id' => $license->customer_id,
            'domain_id'   => $domainId,
            'name'        => $name,
            'key_prefix'  => $prefix,
            'key_hash'    => hash('sha256', $rawKey),
            'status'      => 'active',
        ]);

        return ['model' => $model, 'plain_key' => $rawKey];
    }

    public function revoke(AiApiKey $key): void
    {
        $key->update(['status' => 'revoked', 'revoked_at' => now()]);
    }
}
```

**Kenapa SHA-256, bukan bcrypt/Argon2 (beda dengan hash password):** `bcrypt`/`Argon2` sengaja dibuat lambat untuk menahan brute-force terhadap password yang entropinya rendah (manusia suka pilih password lemah). API key ini digenerate acak 40 karakter (entropi tinggi dari awal) — brute force tidak realistis meskipun pakai hash cepat, dan karena endpoint gateway dipanggil sangat sering, hash lambat seperti bcrypt justru jadi bottleneck performa nyata di setiap request. Pola ini sama seperti yang dipakai Laravel Sanctum sendiri untuk personal access token.

**Tampilkan `plain_key` ke customer HANYA SEKALI** (di response saat key dibuat, atau modal one-time-reveal di UI) — setelah itu hanya `key_prefix` yang ditampilkan (mis. `cooca_ai_live_a1b2c3d4••••••••`), sama seperti pola Stripe/GitHub.

---

## 5. Middleware Autentikasi Gateway

```php
// app/Http/Middleware/Ai/AuthenticateAiApiKey.php
declare(strict_types=1);

namespace App\Http\Middleware\Ai;

use App\Models\AiApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticateAiApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization', '');

        if (!str_starts_with($header, 'Bearer ')) {
            return response()->json(['error' => ['message' => 'Missing API key']], 401);
        }

        $rawKey = substr($header, 7);
        $prefix = substr($rawKey, 0, 12);

        $apiKey = AiApiKey::where('key_prefix', $prefix)
            ->where('status', 'active')
            ->first();

        if (!$apiKey || !hash_equals($apiKey->key_hash, hash('sha256', $rawKey))) {
            return response()->json(['error' => ['message' => 'Invalid API key']], 401);
        }

        $license = $apiKey->license;
        if (!$license || $license->status !== \App\Models\License::STATUS_ACTIVE || $license->expires_at?->isPast()) {
            return response()->json(['error' => ['message' => 'AI module license is not active']], 403);
        }

        $apiKey->update(['last_used_at' => now()]); // ringan, boleh sinkron; kalau traffic tinggi pindahkan ke queue

        // Bind ke request context supaya controller/service berikutnya tidak query ulang
        $request->attributes->set('ai_api_key', $apiKey);
        $request->attributes->set('ai_license', $license);

        return $next($request);
    }
}
```

---

## 6. Endpoint Gateway — Kompatibel Format OpenAI

**Rekomendasi paling penting di bagian ini:** desain endpoint gateway kamu supaya **request/response-nya sama persis dengan format Chat Completions API milik OpenAI**. Konsekuensinya besar: tim developer Bagema/Villa/Wavva (atau kamu sendiri) bisa pakai SDK resmi `openai-php/client` apa adanya, cukup ganti `base_uri` ke domain Cooca dan API key ke key yang diterbitkan Cooca — **nyaris nol effort integrasi**, dan kalau nanti mau ganti provider di baliknya (ke Anthropic dsb.), tenant app sama sekali tidak perlu tahu/ubah kode.

```php
// routes/api.php
Route::prefix('v1/ai')
    ->middleware(['throttle:ai-gateway', \App\Http\Middleware\Ai\AuthenticateAiApiKey::class])
    ->group(function () {
        Route::post('/chat/completions', [\App\Http\Controllers\Api\V1\Ai\ChatCompletionController::class, 'handle']);
    });
```

```php
// app/Http/Controllers/Api/V1/Ai/ChatCompletionController.php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ai;

use App\Http\Controllers\Controller;
use App\Services\Ai\AiGatewayService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class ChatCompletionController extends Controller
{
    public function __construct(private readonly AiGatewayService $gateway) {}

    public function handle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'model'       => 'required|string',
            'messages'    => 'required|array|min:1',
            'temperature' => 'nullable|numeric|min:0|max:2',
            'max_tokens'  => 'nullable|integer|min:1|max:8000',
        ]);

        $apiKey = $request->attributes->get('ai_api_key');
        $license = $request->attributes->get('ai_license');

        $result = $this->gateway->handleChatCompletion($apiKey, $license, $validated);

        return response()->json($result['payload'], $result['status'])
            ->header('X-Cooca-Tokens-Used', (string) $result['tokens_used_this_cycle'])
            ->header('X-Cooca-Tokens-Remaining', (string) $result['tokens_remaining']);
    }
}
```

### 6.1 `AiGatewayService` — orkestrator inti

```php
// app/Services/Ai/AiGatewayService.php
declare(strict_types=1);

namespace App\Services\Ai;

use App\Models\AiApiKey;
use App\Models\License;
use App\Services\Ai\Providers\AiProviderResolver;

final class AiGatewayService
{
    public function __construct(
        private readonly AiQuotaService $quota,
        private readonly AiProviderResolver $providers,
        private readonly AiUsageMeteringService $metering,
    ) {}

    public function handleChatCompletion(AiApiKey $apiKey, License $license, array $payload): array
    {
        $planConfig = $this->quota->planConfigFor($license);

        // 1. Validasi model diizinkan untuk tier plan customer ini
        if (!in_array($payload['model'], $planConfig->allowed_models, true)) {
            return $this->errorResponse(403, "Model '{$payload['model']}' is not available on your current plan.");
        }

        // 2. Cek kuota SEBELUM memanggil provider (hemat biaya kalau sudah pasti ditolak)
        $cycle = $this->quota->currentCycleFor($license); // implementasi: get-or-create cycle
        if ($this->quota->isExhausted($cycle) && $planConfig->overage_policy === 'hard_stop') {
            $this->metering->logRejected($apiKey, $license, $payload['model'], 'quota_exceeded');
            return $this->errorResponse(429, 'Monthly AI token quota exceeded.', extra: [
                'tokens_used' => $cycle->tokens_used,
                'tokens_quota' => $cycle->token_quota,
            ]);
        }

        // 3. Forward ke provider sesungguhnya
        $provider = $this->providers->resolveFor($payload['model']);
        $started = microtime(true);

        try {
            $providerResponse = $provider->chatCompletion($payload);
        } catch (\Throwable $e) {
            $this->metering->logError($apiKey, $license, $payload['model'], $e);
            return $this->errorResponse(502, 'AI provider request failed. Please try again.');
        }

        $durationMs = (int) ((microtime(true) - $started) * 1000);

        // 4. Catat usage & increment counter kuota SETELAH sukses (bukan sebelum —
        //    jangan pernah potong kuota untuk request yang gagal/error di sisi provider)
        $usage = $providerResponse['usage']; // {prompt_tokens, completion_tokens, total_tokens}
        $this->metering->logSuccess($apiKey, $license, $payload['model'], $usage, $durationMs);
        $updatedCycle = $this->quota->increment($cycle, $usage['total_tokens']);

        return [
            'payload' => $providerResponse['body'], // pass-through format OpenAI-compatible
            'status' => 200,
            'tokens_used_this_cycle' => $updatedCycle->tokens_used,
            'tokens_remaining' => max(0, $updatedCycle->token_quota - $updatedCycle->tokens_used),
        ];
    }

    private function errorResponse(int $status, string $message, array $extra = []): array
    {
        return [
            'payload' => ['error' => ['message' => $message, ...$extra]],
            'status' => $status,
            'tokens_used_this_cycle' => 0,
            'tokens_remaining' => 0,
        ];
    }
}
```

### 6.2 Abstraksi Multi-Provider

```php
// app/Services/Ai/Providers/AiProviderInterface.php
interface AiProviderInterface
{
    public function chatCompletion(array $payload): array; // return ['body' => ..., 'usage' => [...]]
}

// app/Services/Ai/Providers/OpenAiProvider.php
final class OpenAiProvider implements AiProviderInterface
{
    public function __construct(private readonly string $apiKey, private readonly string $baseUrl) {}

    public function chatCompletion(array $payload): array
    {
        $response = \Illuminate\Support\Facades\Http::withToken($this->apiKey)
            ->timeout(45) // penting di shared hosting — jangan biarkan tak terbatas, lihat §9
            ->post("{$this->baseUrl}/chat/completions", $payload);

        $response->throw(); // lempar exception kalau 4xx/5xx dari provider

        $body = $response->json();

        return [
            'body' => $body,
            'usage' => [
                'prompt_tokens'     => $body['usage']['prompt_tokens'] ?? 0,
                'completion_tokens' => $body['usage']['completion_tokens'] ?? 0,
                'total_tokens'      => $body['usage']['total_tokens'] ?? 0,
            ],
        ];
    }
}

// app/Services/Ai/Providers/AiProviderResolver.php
final class AiProviderResolver
{
    /** @var array<string, string> model prefix → provider key, mis. "gpt-" => "openai" */
    private const MODEL_PROVIDER_MAP = [
        'gpt-'    => 'openai',
        'claude-' => 'anthropic',
    ];

    public function resolveFor(string $model): AiProviderInterface
    {
        foreach (self::MODEL_PROVIDER_MAP as $prefix => $providerKey) {
            if (str_starts_with($model, $prefix)) {
                return $this->build($providerKey);
            }
        }

        throw new \RuntimeException("No provider mapped for model '{$model}'");
    }

    private function build(string $providerKey): AiProviderInterface
    {
        $config = \App\Models\AiProviderConfig::where('provider', $providerKey)
            ->where('is_active', true)
            ->firstOrFail();

        return match ($providerKey) {
            'openai'    => new \App\Services\Ai\Providers\OpenAiProvider($config->api_key, $config->base_url),
            'anthropic' => new \App\Services\Ai\Providers\AnthropicProvider($config->api_key, $config->base_url),
            default     => throw new \RuntimeException("Unsupported provider '{$providerKey}'"),
        };
    }
}
```

**Catatan format Anthropic:** response API Anthropic strukturnya beda dari OpenAI (field `usage.input_tokens`/`usage.output_tokens`, bukan `prompt_tokens`/`completion_tokens`, dan body response juga beda bentuk). `AnthropicProvider` bertugas **menerjemahkan** response Anthropic ke bentuk OpenAI-compatible sebelum dikembalikan ke tenant — supaya dari sisi tenant app, semua model (baik GPT maupun Claude) terasa seperti satu API yang konsisten. Ini nilai tambah gateway kamu, bukan sekadar pass-through polos.

---

## 7. Rate Limiting (Beda dari Quota — Dua Lapis Proteksi Berbeda)

**Quota** (§2.3) membatasi *total* pemakaian bulanan. **Rate limit** membatasi *kecepatan* request (mencegah 1 tenant nembak ratusan request per detik yang bisa bikin shared hosting kamu keteteran, independen dari sisa kuota mereka masih banyak atau tidak).

```php
// app/Providers/AppServiceProvider.php
RateLimiter::for('ai-gateway', function (Request $request) {
    $apiKey = $request->attributes->get('ai_api_key');

    if (!$apiKey) {
        return Limit::perMinute(5)->by($request->ip()); // belum ter-auth, batasi ketat by IP
    }

    $planConfig = app(\App\Services\Ai\AiQuotaService::class)->planConfigFor($apiKey->license);

    return Limit::perMinute($planConfig->requests_per_minute)->by($apiKey->id);
});
```

Karena rate limit Laravel default pakai cache driver (`database` di shared hosting kamu — lihat dokumen remediation sebelumnya), ini otomatis kompatibel tanpa perlu Redis.

---

## 8. Reset Kuota Bulanan (Cron, Konsisten dengan Pola Shared Hosting Sebelumnya)

```php
// app/Console/Commands/Ai/ResetExpiredUsageCycles.php
declare(strict_types=1);

namespace App\Console\Commands\Ai;

use App\Models\AiUsageCycle;
use App\Models\License;
use App\Services\Ai\AiQuotaService;
use Illuminate\Console\Command;

final class ResetExpiredUsageCycles extends Command
{
    protected $signature = 'ai:reset-usage-cycles';
    protected $description = 'Create the next AiUsageCycle for licenses whose current cycle has ended';

    public function handle(AiQuotaService $quota): int
    {
        $expiredCycles = AiUsageCycle::where('cycle_end', '<', now())
            ->whereDoesntHave('license.usageCycles', fn ($q) => $q->where('cycle_start', '>', now()))
            ->with('license')
            ->get();

        foreach ($expiredCycles as $cycle) {
            if (!$cycle->license || $cycle->license->status !== License::STATUS_ACTIVE) {
                continue; // jangan buat cycle baru untuk license yang sudah tidak aktif
            }

            $quota->startNewCycle($cycle->license);
            $this->info("New cycle created for license {$cycle->license_id}");
        }

        return self::SUCCESS;
    }
}
```

```php
// routes/console.php
Schedule::command('ai:reset-usage-cycles')->daily(); // gabung dengan schedule:run cron yang sudah disiapkan di dokumen sebelumnya
```

---

## 9. Batasan Khusus Shared Hosting untuk Gateway AI

Ini bagian yang paling sering luput kalau desain AI gateway asal contek dari tutorial berbasis VPS/cloud. Karena kamu deploy di shared hosting (konteks dari dokumen remediation sebelumnya), ada beberapa penyesuaian wajib:

1. **Set timeout eksplisit di setiap HTTP call ke provider** (`->timeout(45)` di contoh §6.2). Shared hosting punya `max_execution_time` PHP yang ketat (umumnya 30-60 detik) — kalau request AI lama (model besar, output panjang) dan timeout PHP kena duluan sebelum `Http::timeout()` kena, prosesnya mati paksa di tengah tanpa sempat mencatat usage log, berpotensi bikin biaya ke provider tercatat tapi tidak masuk quota counter kamu (selisih bisa merugikan kamu di sisi bisnis).

2. **Jangan pakai streaming response (SSE) di versi awal.** Banyak shared hosting (Apache+mod_php, atau LiteSpeed) melakukan output buffering di level web server yang tidak bisa dimatikan dari kode PHP saja — hasilnya "streaming" tetap terasa seperti nunggu lama baru muncul sekaligus, tidak dapat manfaat UX streaming sungguhan, malah menambah kompleksitas. Mulai dari response JSON biasa (non-streaming) dulu; evaluasi ulang kalau nanti pindah ke VPS.

3. **Naikkan `max_execution_time` khusus untuk endpoint ini kalau memungkinkan** (lewat `.htaccess` atau `ini_set()` di awal controller) — tapi tetap beri hard limit yang masuk akal (mis. 55 detik) supaya tidak menghabiskan slot proses PHP-FPM/CGI yang terbatas di shared hosting saat traffic ramai.

4. **Metering harus tetap sinkron (bukan lewat queue)** untuk request AI ini — beda dengan job WhatsApp yang boleh async lewat cron-queue. Alasannya: kalau pencatatan usage/quota di-defer ke background job dan job itu belum sempat jalan (siklus cron tiap menit), ada celah waktu di mana tenant bisa mengirim banyak request beruntun yang lolos cek kuota karena counter belum ter-update. Untuk kontrol kuota yang akurat, deduksi kuota **wajib dalam alur request yang sama**, seperti didesain di §6.1.

---

## 10. Dashboard Customer & Admin

### 10.1 Customer — lihat pemakaian sendiri

```php
// app/Http/Controllers/Customer/AiUsageController.php
final class AiUsageController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::user();

        $keys = \App\Models\AiApiKey::where('customer_id', $customer->getKey())->get();
        $cycles = \App\Models\AiUsageCycle::whereIn('license_id', $keys->pluck('license_id')->unique())
            ->where('cycle_start', '<=', now())
            ->where('cycle_end', '>=', now())
            ->get();

        return view('customer.ai.usage', compact('keys', 'cycles'));
    }
}
```

Tampilkan progress bar `tokens_used / token_quota`, dan **kirim notifikasi email otomatis di 80% & 100% pemakaian** (job terjadwal harian yang scan `ai_usage_cycles` mana yang lewat threshold — supaya customer tidak kaget tiba-tiba fitur AI berhenti di tengah kerjaan).

### 10.2 Admin — kontrol & visibilitas bisnis

Halaman admin minimal perlu menampilkan:
- Total tokens terpakai per customer per bulan
- **Margin**: `SUM(cost_usd dari ai_usage_logs)` vs pendapatan langganan AI Module bulan itu — supaya kamu tahu apakah harga plan sudah menutup biaya provider + margin sehat
- Tombol grant bonus token manual (mis. compensasi customer, atau promo) — tambahkan kolom `bonus_tokens` di `ai_usage_cycles` supaya jelas terpisah dari kuota reguler saat audit
- Tombol revoke key darurat

---

## 11. Keamanan — Checklist Tambahan Khusus Modul Ini

- [ ] `ai_provider_configs.api_key` di-cast `'encrypted'` — kredensial asli OpenAI/Anthropic tidak boleh plaintext di DB.
- [ ] `AiApiKey` **tidak pernah** ditampilkan penuh setelah pembuatan pertama (`$hidden = ['key_hash']` di model, response API hanya kirim `plain_key` sekali).
- [ ] Perbandingan hash pakai `hash_equals()`, bukan `===`/`==` (mencegah timing attack, sama seperti perbaikan `WA_WORKER_TOKEN` di dokumen sebelumnya).
- [ ] Endpoint gateway **tidak boleh** menerima parameter yang mengubah `base_url`/provider dari sisi tenant — whitelist model dan provider selalu ditentukan server-side (`ai_plan_configs.allowed_models`), jangan biarkan tenant kirim raw endpoint URL apa pun.
- [ ] Rate limit + quota tetap dicek **meski request datang dari domain tenant yang sama berkali-kali** — jangan asumsikan trafik internal antar produk sendiri otomatis aman dari abuse (bisa saja ada bug di salah satu ERP tenant yang bikin infinite loop pemanggilan AI).
- [ ] Tambahkan test khusus modul ini ke `tests/Feature/Security/` mengikuti pola test suite yang sudah ada: `AiGatewayQuotaEnforcementTest`, `AiApiKeyAuthTest`.

---

## 12. Roadmap Implementasi

```
FASE 1 — Core Gateway (MVP, 1 provider dulu)
  [ ] Migration: ai_api_keys, ai_usage_logs, ai_usage_cycles,
      ai_plan_configs, ai_provider_configs, ai_model_pricing
  [ ] AiApiKeyService (issue & revoke)
  [ ] AuthenticateAiApiKey middleware
  [ ] AiGatewayService + OpenAiProvider (satu provider dulu, Anthropic menyusul)
  [ ] Endpoint POST /api/v1/ai/chat/completions
  [ ] Rate limiting (ai-gateway limiter)
  [ ] Quota enforcement (hard_stop policy dulu, paling sederhana)

FASE 2 — Integrasi Billing & Multi-Provider
  [ ] Product "AI Module" + SubscriptionPlan tiers di admin
  [ ] Listener/Observer auto-issue AiApiKey saat License AI Module aktif
  [ ] AnthropicProvider + AiProviderResolver
  [ ] Cron ai:reset-usage-cycles

FASE 3 — Dashboard & Business Intelligence
  [ ] Customer/AiUsageController + view usage
  [ ] Admin dashboard margin & usage per customer
  [ ] Notifikasi email 80%/100% kuota
  [ ] Fitur bonus token manual dari admin

FASE 4 — Pengerasan & Skala
  [ ] Test suite lengkap (auth, quota race condition, rate limit)
  [ ] Evaluasi overage_policy soft_allow + pay-as-you-go billing (kalau model bisnisnya butuh)
  [ ] Evaluasi kebutuhan streaming response setelah (kalau) pindah dari shared hosting ke VPS
```

---

## 13. Contoh Pemakaian dari Sisi Tenant (Bagema/Villa/Wavva)

```php
// Di dalam salah satu produk ERP Cooca, cukup pakai SDK openai-php/client biasa:
$client = OpenAI::factory()
    ->withApiKey('cooca_ai_live_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') // key dari Cooca, BUKAN dari OpenAI langsung
    ->withBaseUri('api.cooca.id/v1/ai')
    ->make();

$response = $client->chat()->create([
    'model' => 'gpt-4o-mini',
    'messages' => [
        ['role' => 'user', 'content' => 'Buatkan ringkasan laporan penjualan bulan ini.'],
    ],
]);

echo $response->choices[0]->message->content;
// Tenant app SAMA SEKALI tidak tahu (dan tidak perlu tahu) bahwa di baliknya
// mungkin ada 2 provider berbeda, ada quota checking, ada billing —
// semua itu tanggung jawab gateway.
```

---

*Dokumen ini dirancang mengikuti konvensi arsitektur yang sudah ada di codebase `cooca-id` (model `Product`/`SubscriptionPlan`/`License`/`Subscription`/`Customer`/`Domain`) dan batasan shared hosting yang sudah dibahas di dokumen remediation sebelumnya. Sesuaikan nama kolom/relasi kalau ada perubahan skema sejak audit terakhir.*
