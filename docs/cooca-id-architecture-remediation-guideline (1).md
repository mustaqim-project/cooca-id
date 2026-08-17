# Cooca.id — Architecture Remediation Guideline
### Dari Grade C (60/100) menuju Grade A (100/100)

| | |
|---|---|
| **Project** | Cooca.id — Billing/Licensing/Provisioning SaaS Backend |
| **Stack** | Laravel 13.15 · PHP 8.3 · Blade + Livewire 4 + Alpine.js |
| **Skor Saat Ini** | **60 / 100 — Grade C (Refactor Needed)** |
| **Skor Target** | **100 / 100 — Grade A (Production Ready)** |
| **Environment Target** | **Shared Hosting (cPanel, tanpa akses root/WHM)** |
| **Sumber** | Hasil audit end-to-end `laravel-supreme-analyzer-generator`, 11 Agustus 2026 (revisi v2 — disesuaikan untuk shared hosting) |

---

## 1. Ringkasan Eksekutif

> **Revisi v2:** Dokumen ini sudah disesuaikan untuk target deployment **shared hosting cPanel biasa** (bukan VPS/reseller dengan akses root MySQL). Perubahan utama dari versi pertama ada di §5.2 — pendekatan provisioning database diganti total dari raw SQL `CREATE DATABASE`/`GRANT` (yang butuh privilege root, **tidak tersedia** di shared hosting) menjadi pemanggilan **cPanel UAPI**, yang memang dirancang untuk dipakai dari akun hosting biasa tanpa privilege tambahan apa pun.

Codebase ini punya **fondasi arsitektur yang bagus** — struktur direktori enterprise (Actions/DTOs/Services/Repositories/Policies), UUID di semua primary key, dan sudah punya 6 file test suite khusus security (~1.600 baris). Tapi ada **4 celah kritis** yang membuat sistem belum layak produksi:

1. Alur provisioning trial/subscription **akan gagal setiap kali dijalankan** (kolom NOT NULL kosong).
2. SQL mentah tanpa escaping dijalankan dengan asumsi privilege DB root — **privilege ini tidak tersedia di shared hosting**, jadi selain berisiko, pendekatan ini juga tidak akan pernah berfungsi di server produksi kamu sama sekali.
3. Kredensial produksi (password DB + APP_KEY) ter-commit di git.
4. Ada fallback secret hardcoded di kode.

Dokumen ini memetakan **setiap poin yang hilang dari skor 100**, dengan kode before/after, urutan pengerjaan, dan kriteria "selesai" yang terukur — semuanya sudah difilter supaya realistis dijalankan di shared hosting (tidak ada langkah yang butuh Redis daemon, Supervisor, Horizon, atau akses `sudo`/root MySQL).

---

## 2. Peta Skor: Sekarang vs Target

| Dimensi | Sekarang | Target | Gap | Effort |
|---|---|---|---|---|
| Security Posture | 10 / 20 | 20 / 20 | **+10** | Tinggi |
| Performance Design | 5 / 15 | 15 / 15 | **+10** | Sedang |
| Test Coverage | 9 / 15 | 15 / 15 | **+6** | Sedang |
| Database Design | 6 / 10 | 10 / 10 | **+4** | Rendah |
| Separation of Concern | 16 / 20 | 20 / 20 | **+4** | Rendah |
| Code Quality | 11 / 15 | 15 / 15 | **+4** | Rendah |
| API Design | 3 / 5 | 5 / 5 | **+2** | Rendah |
| **TOTAL** | **60 / 100** | **100 / 100** | **+40** | |

---

## 3. Roadmap Pengerjaan (Berurutan, Jangan Dibalik)

```
FASE 0 — STOP THE BLEEDING (hari ini, sebelum kerja lain apapun)
  └─ Rotate kredensial yang sudah bocor di git

FASE 1 — CRITICAL FIXES (Minggu 1)
  └─ Perbaiki alur provisioning yang broken
  └─ Hilangkan raw SQL tanpa escaping + isolasi privilege DB root
  └─ Hilangkan hardcoded default secret

FASE 2 — HIGH PRIORITY (Minggu 2)
  └─ Enkripsi db_password di provisioning_jobs
  └─ Konsolidasi 2 endpoint webhook Midtrans jadi 1
  └─ Konsistenkan pemakaian Policy di semua controller
  └─ Perketat mass assignment

FASE 3 — PERFORMANCE & SCALE (Minggu 3)
  └─ Tambahkan pagination di semua listing
  └─ Audit N+1 query
  └─ Evaluasi driver cache/session/queue untuk beban production

FASE 4 — TEST COVERAGE & POLISH (Minggu 4)
  └─ Tulis test untuk flow provisioning (belum ada sama sekali)
  └─ Pindahkan test payment ke endpoint V1
  └─ Rapikan API design (deprecate endpoint legacy, response envelope konsisten)

FASE 5 — VALIDASI AKHIR
  └─ Re-run audit, target 100/100
```

---

## 4. FASE 0 — Rotasi Kredensial (Lakukan Sebelum Baca Lebih Jauh)

**Kenapa harus duluan:** `.env.example` sudah 220 commit di git berisi `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, dan `APP_KEY` **asli** (bukan placeholder). Selama file itu ada di history git, anggap kredensial itu sudah bocor — apapun status repo (private/public), siapa pun yang pernah/akan punya akses clone punya jalan masuk langsung ke DB produksi dan bisa decrypt cookie/session terenkripsi lewat `APP_KEY`.

### Checklist Fase 0

- [ ] Ganti password user database produksi di hosting/cPanel.
- [ ] Generate `APP_KEY` baru: `php artisan key:generate --force` di server produksi (**catatan:** ini akan membuat semua data yang di-encrypt dengan key lama — termasuk cookie session aktif — tidak terbaca; siapkan jendela maintenance singkat).
- [ ] Update `.env` produksi (bukan `.env.example`) dengan kredensial baru.
- [ ] Bersihkan isi `.env.example` jadi placeholder generik:

```dotenv
# SEBELUM (salah — jangan pernah commit nilai asli)
DB_DATABASE=u218101292_ajlshdlfjhsdlj
DB_USERNAME=u218101292_ajlshdlfjhsdlj
DB_PASSWORD=kJ/3WA3e8d

# SESUDAH (benar)
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

- [ ] Tambahkan `.env` dan varian `.env.*` (kecuali `.env.example`) ke `.gitignore` — cek dulu apakah sudah ada, karena `.env.example` sendiri semestinya *tidak pernah* berisi rahasia sejak awal jadi ini juga soal kebiasaan tim.
- [ ] (Opsional tapi direkomendasikan) Jalankan `git filter-repo` atau BFG Repo-Cleaner untuk menghapus jejak kredensial lama dari history git, lalu force-push — **hanya kalau repo ini private dan kamu satu-satunya kontributor**, karena ini menulis ulang history.
- [ ] Audit ulang seluruh integrasi (Midtrans server key, Sentry DSN, backup S3 keys) yang ada di `.env.example` — pastikan tidak ada nilai asli tersisa di sana juga.

**Kriteria selesai:** `git log -p -- .env.example` tidak menampilkan kredensial asli apa pun di commit terbaru, dan password produksi yang lama sudah tidak valid lagi.

---

## 5. FASE 1 — Critical Fixes

### 5.1 Kenapa Pendekatan Semula Tidak Bisa Jalan di Shared Hosting

Kode asli `ProvisioningEngine` menjalankan `DB::statement("CREATE DATABASE ...")`, `CREATE USER ...`, dan `GRANT ALL PRIVILEGES ...` lewat koneksi MySQL biasa. Ini **butuh privilege setara root MySQL** — sesuatu yang secara desain **tidak pernah diberikan** ke akun shared hosting cPanel. Kredensial `DB_USERNAME`/`DB_PASSWORD` di `.env` kamu hanya boleh mengakses database yang sudah dibuat lewat menu cPanel, tidak bisa `CREATE DATABASE`/`CREATE USER` server-wide.

**Solusi yang benar untuk shared hosting:** jangan bicara langsung ke MySQL. Gunakan **cPanel UAPI** (User API) — endpoint HTTP yang disediakan cPanel sendiri untuk melakukan operasi privileged (create database, create user, set privileges) *atas nama akun hosting kamu*, tanpa app perlu punya privilege MySQL tambahan sama sekali. ini persis fungsi yang sama seperti kalau kamu klik "Create Database" di menu cPanel — hanya saja dipanggil dari kode, bukan diklik manual.

> **Cek dulu ke provider hosting kamu:** hampir semua cPanel modern (versi 11.50+) sudah mengaktifkan UAPI secara default dan bisa dipanggil pakai API Token (dibuat di cPanel → Security → *Manage API Tokens*, tidak perlu password akun). Kalau ternyata UAPI diblokir oleh provider (jarang terjadi tapi ada), lihat opsi fallback di §5.4.

### 5.2 Alur Provisioning — Diperbaiki untuk cPanel UAPI

**Kondisi sekarang:** `ProvisioningService::provisionTrial()`/`provisionSubscription()` tidak mengisi `db_name`/`db_user`/`db_password`/`subdomain` sebelum `ProvJob::create()` (kolom NOT NULL → selalu gagal), **dan** `ProvisioningEngine` mengasumsikan privilege root MySQL yang tidak ada di shared hosting. Dua masalah ini diperbaiki sekaligus dengan mengganti mekanisme provisioning-nya.

**File terdampak:**
- `app/Services/Provisioning/ProvisioningService.php`
- `app/Services/Provisioning/ProvisioningEngine.php`
- File baru: `app/Services/Provisioning/CpanelProvisioningGateway.php`
- `config/services.php`

**Langkah perbaikan:**

1. Buat gateway yang bicara ke cPanel UAPI (menggantikan seluruh `DB::statement()` mentah):

```php
// app/Services/Provisioning/CpanelProvisioningGateway.php
declare(strict_types=1);

namespace App\Services\Provisioning;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class CpanelProvisioningGateway
{
    public function __construct(
        private readonly string $host,        // mis. "yourserver.cloudhosting.id"
        private readonly int $port,           // biasanya 2083
        private readonly string $username,    // username akun cPanel
        private readonly string $apiToken,    // dari cPanel > Security > API Tokens
    ) {}

    public function createDatabase(string $dbNameSuffix): string
    {
        // cPanel OTOMATIS menambahkan prefix "username_" ke nama database.
        // Nama final di server: {cpanel_username}_{dbNameSuffix}
        $response = $this->call('Mysql', 'create_database', ['name' => $dbNameSuffix]);
        $this->assertSuccess($response, "create_database gagal untuk {$dbNameSuffix}");

        return "{$this->username}_{$dbNameSuffix}";
    }

    public function createUser(string $userSuffix, string $password): string
    {
        $response = $this->call('Mysql', 'create_user', [
            'name'     => $userSuffix,
            'password' => $password,
        ]);
        $this->assertSuccess($response, "create_user gagal untuk {$userSuffix}");

        return "{$this->username}_{$userSuffix}";
    }

    public function grantAllPrivileges(string $fullDbName, string $fullDbUser): void
    {
        $response = $this->call('Mysql', 'set_privileges_on_user', [
            'user'       => $fullDbUser,
            'database'   => $fullDbName,
            'privileges' => 'ALL PRIVILEGES',
        ]);
        $this->assertSuccess($response, "set_privileges gagal untuk {$fullDbUser} @ {$fullDbName}");
    }

    public function dropDatabase(string $fullDbName): void
    {
        $suffix = $this->stripAccountPrefix($fullDbName);
        $this->call('Mysql', 'delete_database', ['name' => $suffix]);
    }

    public function dropUser(string $fullDbUser): void
    {
        $suffix = $this->stripAccountPrefix($fullDbUser);
        $this->call('Mysql', 'delete_user', ['name' => $suffix]);
    }

    private function call(string $module, string $function, array $params): array
    {
        $url = "https://{$this->host}:{$this->port}/execute/{$module}/{$function}";

        $response = Http::withHeaders([
                'Authorization' => "cpanel {$this->username}:{$this->apiToken}",
            ])
            ->timeout(30)
            ->get($url, $params);

        Log::info("CpanelProvisioningGateway: {$module}.{$function}", [
            'params' => array_diff_key($params, ['password' => '']), // jangan log password
            'status' => $response->status(),
        ]);

        return $response->json() ?? [];
    }

    private function assertSuccess(array $response, string $errorContext): void
    {
        if (($response['status'] ?? 0) !== 1) {
            $message = $response['errors'][0] ?? 'Unknown cPanel UAPI error';
            throw new \RuntimeException("{$errorContext}: {$message}");
        }
    }

    private function stripAccountPrefix(string $value): string
    {
        return str_starts_with($value, "{$this->username}_")
            ? substr($value, strlen($this->username) + 1)
            : $value;
    }
}
```

```php
// config/services.php
'cpanel' => [
    'host'      => env('CPANEL_HOST'),
    'port'      => env('CPANEL_PORT', 2083),
    'username'  => env('CPANEL_USERNAME'),
    'api_token' => env('CPANEL_API_TOKEN'),
],
```

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->singleton(CpanelProvisioningGateway::class, function () {
        return new CpanelProvisioningGateway(
            host: config('services.cpanel.host'),
            port: (int) config('services.cpanel.port'),
            username: config('services.cpanel.username'),
            apiToken: config('services.cpanel.api_token'),
        );
    });
}
```

2. **Penting — batasan nama identifier cPanel.** cPanel menambahkan prefix `{username_akun}_` ke setiap nama database/user, dan sebagian besar hosting membatasi total panjang nama database/user MySQL sampai 16 karakter *setelah* prefix (batasan lama MySQL/cPanel yang masih umum dipakai). Karena itu, generator kredensial harus menghasilkan suffix pendek:

```php
// app/Services/Provisioning/TenantCredentialGenerator.php
declare(strict_types=1);

namespace App\Services\Provisioning;

use Illuminate\Support\Str;

final class TenantCredentialGenerator
{
    public function generate(string $tenantUuid): array
    {
        // Suffix sengaja pendek (6-8 karakter) supaya total nama setelah
        // prefix cPanel ("username_") tetap aman di bawah batas 16 karakter
        // yang masih dipakai banyak provider shared hosting.
        $shortId = strtolower(Str::substr(str_replace('-', '', $tenantUuid), 0, 6));

        return [
            'db_name_suffix' => "cc{$shortId}",       // mis. "ccab12f9"
            'db_user_suffix' => "cc{$shortId}",
            'db_password'    => Str::password(20, symbols: false), // alfanumerik, hindari karakter yang perlu escaping ekstra di config koneksi
        ];
    }
}
```

   > Verifikasi batas karakter pasti ke provider hosting kamu — beberapa cPanel versi baru sudah mendukung nama lebih panjang. Kalau ragu, tetap pakai suffix pendek supaya kompatibel di provider mana pun.

3. Rangkai semuanya di `ProvisioningService` — isi `ProvJob` dengan lengkap **sebelum** disimpan:

```php
// app/Services/Provisioning/ProvisioningService.php

public function __construct(
    private readonly ProvisioningEngine $engine,
    private readonly TenantCredentialGenerator $credentialGenerator, // tambah dependency
) {}

public function provisionTrial(Trial $trial): ProvJob
{
    return DB::transaction(function () use ($trial) {
        $domain = $this->createDomainRecord($trial);
        $erpRequest = $this->createErpRequest($trial, $domain);

        $tenantUuid = (string) Str::uuid();
        $credentials = $this->credentialGenerator->generate($tenantUuid);

        $job = ProvJob::create([
            'erp_request_id'  => $erpRequest->id,
            'tenant_type'     => 'trial',
            'tenant_id'       => $trial->id,
            'tenant_uuid'     => $tenantUuid,
            // Simpan suffix di sini — nama final (dengan prefix cPanel)
            // baru diketahui setelah createDatabase()/createUser() dipanggil
            // di ProvisioningEngine, lalu di-update kembali ke job ini.
            'db_name'         => $credentials['db_name_suffix'],
            'db_user'         => $credentials['db_user_suffix'],
            'db_password'     => $credentials['db_password'],
            'subdomain'       => $trial->subdomain,
            'status'          => 'pending',
            'current_step'    => 'init',
            'attempts'        => 0,
            'max_attempts'    => 3,
            'metadata' => [
                'trial_id'    => $trial->id,
                'customer_id' => $trial->customer_id,
                'product_id'  => $trial->erp_product_id,
                'subdomain'   => $trial->subdomain,
            ],
        ]);

        return $job;
    });
}
```

Terapkan pola yang sama di `provisionSubscription()`.

4. Ganti isi `ProvisioningEngine::stepCreateDb()` — tidak ada lagi `DB::statement()` mentah sama sekali:

```php
// app/Services/Provisioning/ProvisioningEngine.php
declare(strict_types=1);

namespace App\Services\Provisioning;

// ... use statements lain tetap, tambahkan:
use App\Services\Provisioning\CpanelProvisioningGateway;

final class ProvisioningEngine
{
    public function __construct(
        private readonly CpanelProvisioningGateway $cpanel,
    ) {}

    // ...

    private function stepCreateDb(ProvJob $job): void
    {
        Log::info("ProvisioningEngine: Creating DB via cPanel UAPI for job {$job->id}");

        // Validasi tetap dipertahankan — defense in depth meski sudah tidak
        // ada raw SQL, karena nilai ini tetap dikirim sebagai parameter API.
        foreach (['db_name' => $job->db_name, 'db_user' => $job->db_user] as $label => $value) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $value)) {
                throw new \RuntimeException("Invalid {$label} format for provisioning job {$job->id}");
            }
        }

        $fullDbName = $this->cpanel->createDatabase($job->db_name);
        $fullDbUser = $this->cpanel->createUser($job->db_user, $job->db_password);
        $this->cpanel->grantAllPrivileges($fullDbName, $fullDbUser);

        // Simpan nama LENGKAP (dengan prefix cPanel) supaya step migrate()
        // tahu nama database/user yang sebenarnya di server.
        $job->update([
            'db_name'      => $fullDbName,
            'db_user'      => $fullDbUser,
            'current_step' => 'migrate',
        ]);
    }

    public function rollback(ProvJob $job): void
    {
        Log::warning("ProvisioningEngine: Rolling back job {$job->id} via cPanel UAPI");

        try {
            $this->cpanel->dropDatabase($job->db_name);
            $this->cpanel->dropUser($job->db_user);
        } catch (\Exception $e) {
            Log::error("ProvisioningEngine: Rollback via cPanel UAPI failed", ['error' => $e->getMessage()]);
        }

        Domain::where('erp_request_id', $job->erp_request_id)->delete();
        License::where('erp_request_id', $job->erp_request_id)->delete();

        $job->update(['status' => 'rolled_back']);
        if ($job->erpRequest) {
            $job->erpRequest->update([
                'status' => ErpRequest::STATUS_REJECTED,
                'notes'  => 'Rolled back after failure.',
            ]);
        }
    }
}
```

**Kenapa ini otomatis lebih aman, bukan cuma "lebih cocok untuk shared hosting":**
- Tidak ada string SQL yang dirakit manual sama sekali → risiko SQL injection ke statement DDL hilang total, bukan cuma dimitigasi.
- cPanel UAPI **secara native membatasi** operasi hanya ke lingkup akun hosting kamu sendiri — tidak mungkin (secara desain server) operasi ini menyentuh akun/database milik tenant hosting lain. Isolasi privilege yang di versi pertama dokumen ini perlu disiapkan manual (koneksi DB terpisah), sekarang didapat gratis dari cara kerja cPanel.
- API Token cPanel bisa di-revoke kapan saja dari dashboard tanpa mengganti password akun hosting.

**Kriteria selesai:**
- [ ] Test baru `tests/Feature/Provisioning/TrialProvisioningFlowTest.php` yang menjalankan `provisionTrial()` end-to-end sampai `ProvJob` tersimpan lengkap tanpa exception (gunakan `Http::fake()` untuk mock respons UAPI di test).
- [ ] Manual test di staging: request trial baru dari UI customer, cek database benar-benar muncul di menu cPanel → MySQL Databases.
- [ ] `grep -rn "DB::statement" app/Services/Provisioning/` menghasilkan nol baris.

---

### 5.3 Hardcoded Default Secret

**File:** `app/Http/Controllers/Api/WhatsAppWorkerController.php:10`

```php
// SEBELUM
$expectedToken = env('WA_WORKER_TOKEN', 'secret-worker-token');
if (!$token || $token !== $expectedToken) {
    abort(401, 'Unauthorized Worker');
}
```

```php
// SESUDAH
$expectedToken = config('services.wa_worker.token');
if (empty($expectedToken)) {
    // Fail closed, bukan fail open ke default yang bisa ditebak.
    Log::critical('WA_WORKER_TOKEN is not configured — worker endpoints are unreachable.');
    abort(500, 'Worker authentication is not configured');
}

if (!$token || !hash_equals($expectedToken, $token)) {
    abort(401, 'Unauthorized Worker');
}
```

```php
// config/services.php
'wa_worker' => [
    'token' => env('WA_WORKER_TOKEN'),
],
```

Catatan tambahan: ganti `!==` jadi `hash_equals()` sekalian — perbandingan token dengan `!==` rentan *timing attack* (kecil risikonya di sini, tapi gratis untuk diperbaiki sekalian).

**Kriteria selesai:**
- [ ] `grep -rn "env(" app/Http/Controllers` tidak lagi menampilkan pemanggilan `env()` langsung di controller (semua lewat `config()`).
- [ ] Tidak ada default value untuk kredensial/token apa pun di codebase — kalau env kosong, sistem harus gagal secara eksplisit (fail closed), bukan diam-diam pakai nilai default.

---

### 5.4 Opsi Fallback — Kalau cPanel UAPI Ternyata Diblokir Provider

Sebagian kecil provider shared hosting menonaktifkan akses API token atau membatasi outbound HTTPS request dari PHP. Kalau setelah dicoba `CpanelProvisioningGateway` gagal terus dengan error koneksi (bukan error kredensial), berikut dua opsi realistis lain — urutkan sesuai preferensi:

**Opsi B — Pool database pre-provisioned (paling cepat diimplementasikan, tidak butuh API sama sekali)**

Alih-alih membuat database baru *real-time* saat ada trial masuk, siapkan sejumlah database+user kosong **di muka** lewat cPanel UI (manual, sekali kerja), simpan datanya di tabel, lalu saat ada trial baru aplikasi tinggal "klaim" satu slot yang belum terpakai:

```php
// database/migrations/..._create_tenant_db_pool_table.php
Schema::create('tenant_db_pool', function (Blueprint $table) {
    $table->id();
    $table->string('db_name');       // sudah dibuat manual lewat cPanel
    $table->string('db_user');       // sudah dibuat manual lewat cPanel
    $table->string('db_password');   // di-set manual saat create user, simpan terenkripsi
    $table->foreignId('claimed_by_provisioning_job_id')->nullable()
        ->constrained('provisioning_jobs')->nullOnDelete();
    $table->timestamp('claimed_at')->nullable();
    $table->timestamps();
});
```

```php
// app/Services/Provisioning/DbPoolClaimService.php
final class DbPoolClaimService
{
    public function claimSlotFor(ProvJob $job): TenantDbPool
    {
        return DB::transaction(function () use ($job) {
            $slot = TenantDbPool::whereNull('claimed_by_provisioning_job_id')
                ->lockForUpdate()
                ->firstOrFail(); // kalau kosong, berarti pool perlu diisi ulang manual

            $slot->update([
                'claimed_by_provisioning_job_id' => $job->id,
                'claimed_at' => now(),
            ]);

            return $slot;
        });
    }
}
```

Kekurangan: perlu monitoring manual supaya pool tidak habis (misal alert kalau slot tersisa < 5), dan menambah slot baru tetap kerja manual lewat cPanel setiap kali stok menipis. Tapi ini **paling minim risiko teknis** karena tidak bergantung API pihak ketiga sama sekali.

**Opsi C — Single-database multi-tenancy (paling scalable, tapi effort paling besar)**

Semua tenant berbagi satu database (yang sudah ada) dan dipisahkan lewat kolom `tenant_id` + Eloquent Global Scope otomatis, bukan lewat database fisik terpisah. **Catatan penting:** opsi ini **bukan cuma perubahan di `cooca-id`** — aplikasi ERP tenant itu sendiri (mis. [[bagema-erp]], [[villa-restaurant-erp]], [[wavva-pool-booking]]) juga harus direkayasa ulang supaya *tenant-aware* (setiap query di ERP tersebut wajib difilter `tenant_id`). Ini proyek terpisah yang jauh lebih besar dari sekadar memperbaiki `cooca-id`, jadi **hanya pertimbangkan opsi ini kalau Opsi A dan B benar-benar tidak bisa dipakai**, dan rencanakan sebagai inisiatif arsitektur tersendiri, bukan bagian dari remediation ini.

**Rekomendasi:** mulai dari Opsi A (§5.2). Kalau providermu ternyata tidak mendukung UAPI, turun ke Opsi B sebagai solusi cepat sambil evaluasi pindah provider hosting yang mendukung UAPI (kebanyakan provider besar di Indonesia mendukungnya) — jauh lebih murah daripada menulis ulang arsitektur ERP tenant demi Opsi C.

---

## 6. FASE 2 — High Priority

### 6.1 Enkripsi `db_password` di `provisioning_jobs`

```php
// app/Models/ProvisioningJob.php
protected $casts = [
    'db_password' => 'encrypted',
];
```

Karena kolomnya `string`, Laravel akan meng-encrypt/decrypt otomatis pakai `APP_KEY` setiap kali diakses lewat Eloquent. **Penting:** setelah menambahkan cast ini, tulis migration data untuk meng-enkripsi baris yang sudah ada (kalau ada data lama), dan pastikan ini dilakukan **setelah** rotasi `APP_KEY` di Fase 0, bukan sebelumnya.

**Kriteria selesai:** `SELECT db_password FROM provisioning_jobs LIMIT 1` di database langsung menampilkan ciphertext, bukan plaintext.

---

### 6.2 Konsolidasi Endpoint Webhook Midtrans

**Kondisi sekarang:** dua endpoint aktif bersamaan — `/api/midtrans/webhook` (legacy, tanpa cek tamper-amount) dan `/api/v1/midtrans/webhook` (baru, dengan cek tamper-amount). Test suite justru lebih banyak menguji yang legacy.

**Langkah perbaikan (pilih salah satu strategi):**

**Opsi A — Migrasi penuh (direkomendasikan):**
1. Login ke dashboard Midtrans, update Payment Notification URL ke `/api/v1/midtrans/webhook`.
2. Monitor log selama minimal 7 hari untuk pastikan tidak ada lagi trafik masuk ke endpoint legacy.
3. Hapus route legacy dari `routes/api.php`:

```php
// routes/api.php — HAPUS blok ini setelah migrasi selesai
Route::middleware(['throttle:midtrans-webhook'])->group(function () {
    Route::post('/midtrans/webhook', [WebhookController::class, 'handle'])
        ->name('api.midtrans.webhook.legacy');
});
```

4. Hapus `app/Http/Controllers/Midtrans/WebhookController.php`.
5. Pindahkan test yang relevan dari `tests/Feature/Payment/MidtransWebhookSignatureTest.php` dan `tests/Feature/Security/PaymentSecurityTest.php` supaya menguji `/api/v1/midtrans/webhook`, bukan endpoint legacy.

**Opsi B — Kalau belum bisa migrasi (misal ada integrasi lama yang masih hardcode URL lama):**
Porting logic tamper-amount check dari controller V1 ke legacy sebagai mitigasi sementara, sambil tetap menjadwalkan Opsi A.

**Kriteria selesai:**
- [ ] Hanya ada 1 endpoint webhook Midtrans yang terdaftar di `routes/api.php`.
- [ ] Semua test payment security menguji endpoint yang benar-benar dipakai di produksi.

---

### 6.3 Konsistensi Pemakaian Policy

**Kondisi sekarang:** 13 file Policy ditulis, hanya 2 controller yang memanggilnya. Sisanya bergantung pada `where('customer_id', ...)` manual di setiap query — bekerja, tapi rapuh karena tidak ada satu titik enforcement terpusat.

**Langkah perbaikan — terapkan pola ini di semua controller yang modelnya punya Policy:**

```php
// SEBELUM (app/Http/Controllers/Customer/LicenseController.php)
public function show(string $id)
{
    $customer = Auth::user();
    $license = \App\Models\License::where('id', $id)->where('customer_id', $customer->getKey())->first();

    if (!$license) {
        abort(404, 'License not found');
    }

    return view('customer.licenses.show', ['license' => new LicenseResource($license)]);
}
```

```php
// SESUDAH — scoping manual TETAP DIPERTAHANKAN (defense in depth, mencegah
// timing/enumeration di skala tertentu), tapi authorize() jadi enforcement
// eksplisit yang konsisten dan mudah diaudit.
public function show(string $id)
{
    $license = \App\Models\License::where('id', $id)
        ->where('customer_id', Auth::id())
        ->firstOrFail();

    $this->authorize('view', $license);

    return view('customer.licenses.show', ['license' => new LicenseResource($license)]);
}
```

```php
// app/Policies/LicensePolicy.php
public function view(Customer $customer, License $license): bool
{
    return $customer->id === $license->customer_id;
}
```

Lakukan audit satu per satu untuk 13 model yang punya Policy (`Customer`, `Invoice`, `License`, `Subscription`, `Ticket`, `Transaction`, `Voucher`, `Domain`, `Affiliator`, `AffiliateCommission`, `AffiliateWithdrawal`, `ErpRequest`, `Review`) — pastikan setiap controller Customer/Affiliator yang mengakses resource itu memanggil `$this->authorize()`.

**Kriteria selesai:**
- [ ] `grep -rln "authorize(" app/Http/Controllers` menghasilkan minimal 13+ file (satu per model yang punya Policy, bisa lebih kalau satu controller handle beberapa action).
- [ ] Test baru per Policy: pastikan customer A tidak bisa `authorize('view', $resourceMilikB)`.

---

### 6.4 Perketat Mass Assignment

**Kondisi sekarang:** base model `App\Models\Model` pakai `$guarded = ['id','created_at','updated_at','deleted_at']` — semua kolom lain otomatis *fillable*. Ditemukan minimal 1 titik nyata yang memakai `$request->all()` langsung ke `create()`.

**Langkah perbaikan:**

1. Perbaiki instance yang sudah ditemukan:

```php
// SEBELUM — app/Http/Controllers/Admin/AccountingController.php:31
ChartOfAccount::create($request->all());
```

```php
// SESUDAH
public function store(StoreChartOfAccountRequest $request)
{
    ChartOfAccount::create($request->validated());
}
```

Buat `StoreChartOfAccountRequest` kalau belum ada, dengan rules eksplisit untuk setiap field.

2. Audit seluruh codebase untuk pola serupa:

```bash
grep -rn "::create(\$request->all())\|->update(\$request->all())\|::create(request()->all())" app/
```

3. Untuk model-model finansial berisiko tinggi (`Invoice`, `Transaction`, `ChartOfAccount`, `License`, `Payment`, `Subscription`), pertimbangkan override `$fillable` eksplisit di level model (lebih ketat daripada mengandalkan `$guarded` global):

```php
// app/Models/Invoice.php
protected $fillable = [
    'customer_id', 'subscription_id', 'invoice_number',
    'amount', 'due_date', 'status', 'notes',
    // secara sengaja TIDAK termasuk field seperti 'paid_at', 'verified_by'
    // yang seharusnya hanya diubah lewat method service tertentu.
];
```

**Kriteria selesai:**
- [ ] Nol hasil dari grep pattern di atas.
- [ ] Semua model finansial punya `$fillable` eksplisit yang mengecualikan field status/audit yang seharusnya hanya diubah lewat business logic, bukan input user langsung.

---

## 7. FASE 3 — Performance & Scale

### 7.1 Pagination di Semua Listing

**Kondisi sekarang:** 0 pemakaian `->paginate()` di seluruh `app/Http/Controllers`; 50 file pakai `::all()`/`->get()` langsung.

**Pola perbaikan (terapkan di semua controller listing, contoh untuk `CustomerController`):**

```php
// SEBELUM
public function index()
{
    $customers = Customer::with('subscriptions')->get();
    return view('admin.customers.index', compact('customers'));
}
```

```php
// SESUDAH
public function index(Request $request)
{
    $customers = Customer::with('subscriptions')
        ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
        ->latest()
        ->paginate(25)
        ->withQueryString();

    return view('admin.customers.index', compact('customers'));
}
```

**Prioritaskan dulu controller dengan data yang paling cepat tumbuh** (urutan pengerjaan):
1. `Admin/CustomerController`, `Admin/TransactionController`, `Admin/FinanceController`, `Admin/AccountingController`
2. `Admin/AuditLogController`, `Admin/ErrorLogController` (log tumbuh paling cepat)
3. `Admin/DashboardController` (agregat — evaluasi apakah butuh cache, bukan hanya pagination)
4. Sisanya (50 file total, kerjakan bertahap per modul)

**Kriteria selesai:** `grep -rn "->paginate(" app/Http/Controllers | wc -l` naik dari 0 ke minimal jumlah controller listing yang teridentifikasi (targetkan seluruh 50 titik yang pakai `::all()`/`->get()` tanpa limit).

---

### 7.2 Audit N+1 Query

**Langkah kerja:**

1. Install `barryvdh/laravel-debugbar` (dev only) atau aktifkan `DB::listen()` sementara di lokal untuk mendeteksi query berulang.
2. Untuk setiap listing yang menampilkan data relasi (misal `Customer` + jumlah `subscriptions`, `License` + `subscription`), pastikan pakai eager loading:

```php
// SEBELUM (rawan N+1 kalau dipanggil dari Blade loop)
$licenses = License::where('customer_id', $id)->get();
// di view: @foreach($licenses as $l) {{ $l->subscription->plan_name }} @endforeach
// ^ setiap iterasi trigger query baru ke tabel subscriptions

// SESUDAH
$licenses = License::with('subscription.subscriptionPlan')
    ->where('customer_id', $id)
    ->get();
```

3. Tambahkan guard permanen di `AppServiceProvider` (development only) supaya lazy-loading N+1 langsung ketahuan saat testing/development, bukan baru ketahuan di produksi:

```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
    Model::preventLazyLoading(! app()->isProduction());
}
```

**Kriteria selesai:**
- [ ] Semua listing utama (dashboard admin, listing customer, listing transaksi) diverifikasi jumlah query-nya tidak bertambah seiring jumlah baris data (test dengan debugbar: cek query count tetap konstan meski data di-seed 100 vs 1000 baris).
- [ ] `Model::preventLazyLoading()` aktif di environment non-production.

---

### 7.3 Queue & Cache di Shared Hosting (Realistis, Bukan Asumsi VPS)

**Kondisi sekarang:** `CACHE_STORE=database`, `SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`. Ini **memang pilihan yang tepat untuk shared hosting**, bukan sekadar "sementara" — karena shared hosting cPanel pada umumnya:
- **Tidak menyediakan Redis** sebagai service (kecuali beberapa provider premium menawarkan sebagai addon berbayar — cek dulu ke provider kamu sebelum asumsikan tidak ada).
- **Tidak mengizinkan proses daemon/background yang berjalan terus-menerus** — jadi `php artisan queue:work` (yang didesain jalan selamanya) **tidak bisa** dijalankan lewat Supervisor, dan `laravel/horizon` (yang butuh Redis + proses daemon) juga tidak relevan di sini.

**Masalah nyata yang perlu diselesaikan bukan "ganti ke Redis", tapi "bagaimana queue job (WhatsApp gateway, provisioning) tetap jalan tanpa daemon process".**

**Langkah perbaikan — pola queue lewat Cron Job (didukung semua cPanel):**

1. Di cPanel → **Cron Jobs**, jadwalkan command ini setiap menit:

```bash
* * * * * cd /home/username/public_html && php artisan schedule:run >> /dev/null 2>&1
```

2. Di dalam `routes/console.php` (Laravel 11+) atau `app/Console/Kernel.php`, daftarkan queue worker supaya dijalankan lewat scheduler — bukan proses daemon terus-menerus, tapi "kerjakan job yang antre, lalu berhenti":

```php
// routes/console.php
use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:work --stop-when-empty --max-time=50 --tries=3')
    ->everyMinute()
    ->withoutOverlapping(3) // cegah 2 instance jalan bersamaan kalau job lama > 1 menit
    ->runInBackground();
```

   `--max-time=50` penting: banyak shared hosting punya batas eksekusi PHP CLI per proses (umumnya 60–300 detik tergantung provider) — nilai ini memastikan worker berhenti sendiri sebelum kena kill paksa oleh hosting, supaya job yang sedang diproses tidak korup di tengah jalan.

3. **Khusus job WhatsApp gateway** (kalau volumenya tinggi — banyak pesan per menit): pertimbangkan queue terpisah dengan prioritas, tetap lewat mekanisme yang sama:

```php
Schedule::command('queue:work --queue=whatsapp,default --stop-when-empty --max-time=50')
    ->everyMinute()
    ->withoutOverlapping(3)
    ->runInBackground();
```

4. **Index yang perlu dipastikan ada** di tabel `jobs`, `cache`, `sessions` (biasanya sudah default dari migration bawaan Laravel, tapi cek ulang):

```php
// Pastikan migration default Laravel untuk jobs/cache/sessions belum dimodifikasi
// dan index bawaan (primary key, index pada 'queue', 'reserved_at') masih utuh.
```

**Kapan baru pertimbangkan pindah dari pola ini** (bukan sekarang, tapi dokumentasikan threshold-nya supaya keputusan nanti berbasis data):
- Kalau rata-rata jumlah job yang antre di tabel `jobs` konsisten > beberapa ratus dan tidak habis dalam 1 menit siklus cron → pertimbangkan upgrade ke VPS supaya bisa pakai Supervisor + Redis + Horizon.
- Kalau provider hosting kamu ternyata *memang* menyediakan Redis sebagai addon → `CACHE_STORE=redis` bisa diaktifkan lebih dulu (paling murah dampaknya, paling sedikit risiko), sebelum menyentuh `QUEUE_CONNECTION`.

**Kriteria selesai:**
- [ ] Cron job `schedule:run` terdaftar dan terverifikasi jalan tiap menit (cek `storage/logs/laravel.log` untuk konfirmasi job terproses).
- [ ] `queue:work` di scheduler pakai `--stop-when-empty` dan `--max-time`, tidak dijalankan sebagai proses daemon manual.
- [ ] Threshold migrasi ke VPS/Redis didokumentasikan (misal di README atau dokumen ops internal).

---

## 8. FASE 4 — Test Coverage & Polish

### 8.1 Test untuk Flow Provisioning (Belum Ada Sama Sekali)

```php
// tests/Feature/Provisioning/TrialProvisioningFlowTest.php
declare(strict_types=1);

namespace Tests\Feature\Provisioning;

use App\Models\Trial;
use App\Models\ProvisioningJob;
use App\Services\Provisioning\ProvisioningService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

final class TrialProvisioningFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_provision_trial_creates_job_with_all_required_fields(): void
    {
        $trial = Trial::factory()->create();

        $job = app(ProvisioningService::class)->provisionTrial($trial);

        $this->assertDatabaseHas('provisioning_jobs', [
            'id' => $job->id,
            'tenant_type' => 'trial',
        ]);

        $job->refresh();
        $this->assertNotEmpty($job->db_name);
        $this->assertNotEmpty($job->db_user);
        $this->assertNotEmpty($job->db_password);
        $this->assertNotEmpty($job->subdomain);
    }

    public function test_provisioning_engine_rejects_unsafe_db_credentials(): void
    {
        $job = ProvisioningJob::factory()->create([
            'db_name' => "malicious`; DROP TABLE users; --",
        ]);

        $this->expectException(\RuntimeException::class);

        app(\App\Services\Provisioning\ProvisioningEngine::class)->run($job);
    }
}
```

(Sesuaikan dengan factory yang sudah ada di project — buat `TrialFactory` dan `ProvisioningJobFactory` kalau belum ada.)

### 8.2 Pindahkan Fokus Test Payment ke Endpoint V1

Sudah dibahas di 6.2 — pastikan setelah konsolidasi, `tests/Feature/Payment/*` dan `tests/Feature/Security/PaymentSecurityTest.php` menguji `/api/v1/midtrans/webhook`.

### 8.3 Target Tambahan Test Coverage

| Area | Test yang perlu ditambahkan |
|---|---|
| Policy enforcement | 1 test per Policy: user lain tidak bisa akses resource |
| Mass assignment | Test bahwa field yang di-guard tidak bisa di-inject lewat request body |
| Rate limiting | Test bahwa endpoint `/v1/license/validate` menolak setelah N request/menit |
| WhatsApp worker auth | Test bahwa endpoint gagal (500) kalau `WA_WORKER_TOKEN` tidak di-set, bukan pakai default |

**Kriteria selesai:** `php artisan test` hijau semua, dan coverage report (`php artisan test --coverage`) menunjukkan flow provisioning + payment + authorization masuk dalam cakupan test.

---

## 9. FASE 4 (lanjutan) — API Design

### 9.1 Rate Limiting di Endpoint Publik

```php
// routes/api.php
Route::post('/v1/license/validate', [\App\Http\Controllers\Api\V1\LicenseValidationController::class, 'validate'])
    ->middleware('throttle:license-validation')
    ->name('api.license.validate');
```

```php
// app/Providers/AppServiceProvider.php (atau RouteServiceProvider kalau masih ada)
RateLimiter::for('license-validation', function (Request $request) {
    return Limit::perMinute(30)->by($request->ip());
});
```

### 9.2 Response Envelope Konsisten

Standarkan format response API di seluruh endpoint (saat ini bercampur antara `{success, message, data}` dan `{status, message}`):

```php
// app/Http/Responses/ApiResponse.php
final class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json(['success' => true, 'message' => $message, 'data' => $data], $code);
    }

    public static function error(string $message, int $code = 400, mixed $errors = null): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message, 'errors' => $errors], $code);
    }
}
```

Ganti semua `response()->json([...])` manual di controller API untuk pakai helper ini.

**Kriteria selesai:**
- [ ] Semua endpoint publik/berat trafik punya rate limit eksplisit.
- [ ] Semua response API (V1) memakai format envelope yang sama.

---

## 10. Checklist Master (Cetak & Centang)

```
FASE 0 — KREDENSIAL
  [ ] Password DB produksi diganti
  [ ] APP_KEY diganti (dengan maintenance window)
  [ ] .env.example dibersihkan jadi placeholder
  [ ] Git history dibersihkan (kalau memungkinkan)

FASE 1 — CRITICAL
  [ ] CpanelProvisioningGateway dibuat & CPANEL_* env terisi (host/username/API token)
  [ ] TenantCredentialGenerator dibuat & dipakai (suffix pendek, alfanumerik)
  [ ] ProvisioningService mengisi db_name/db_user/db_password/subdomain sebelum ProvJob::create()
  [ ] ProvisioningEngine::stepCreateDb() & rollback() sudah 0% pakai DB::statement mentah
  [ ] Test manual: database benar-benar muncul di cPanel > MySQL Databases setelah trial dibuat
  [ ] WA_WORKER_TOKEN tidak punya default value, fail closed

FASE 2 — HIGH
  [ ] db_password di-cast 'encrypted'
  [ ] Endpoint webhook Midtrans legacy dinonaktifkan/dihapus
  [ ] Test payment dipindah ke endpoint V1
  [ ] authorize() dipanggil konsisten di semua controller ber-Policy
  [ ] Semua ::create($request->all()) diganti $request->validated()
  [ ] Model finansial punya $fillable eksplisit

FASE 3 — PERFORMANCE
  [ ] Semua listing admin pakai ->paginate()
  [ ] Model::preventLazyLoading() aktif di non-production
  [ ] Eager loading diverifikasi di semua listing dengan relasi
  [ ] Cron job schedule:run terpasang & queue:work jalan via scheduler (bukan daemon)
  [ ] Threshold migrasi ke VPS/Redis didokumentasikan (bukan dikerjakan sekarang)

FASE 4 — TEST & API
  [ ] Test flow provisioning trial & subscription
  [ ] Test rejection kredensial DB tidak valid
  [ ] Rate limit di /v1/license/validate
  [ ] Response envelope API konsisten

FASE 5 — VALIDASI
  [ ] Re-run /laravel-supreme-analyzer-generator, target skor 100/100
```

---

## 11. Cara Skor Ini Dihitung Ulang Jadi 100/100

| Dimensi | Syarat Skor Maksimal |
|---|---|
| **Security (20)** | Tidak ada kredensial hardcoded/default, tidak ada raw SQL tanpa validasi, semua endpoint sensitif punya auth + rate limit, Policy diterapkan konsisten, data sensitif (password tenant) terenkripsi. |
| **Performance (15)** | Semua listing berpotensi besar pakai pagination, N+1 diverifikasi nol lewat `preventLazyLoading`, strategi cache/queue terdokumentasi dengan threshold jelas. |
| **Test Coverage (15)** | Flow bisnis inti (provisioning, payment, licensing) punya test end-to-end, bukan hanya unit test komponen kecil; test menguji endpoint yang benar-benar dipakai produksi. |
| **Database Design (10)** | Kolom sensitif ter-enkripsi, tidak ada kolom NOT NULL yang berpotensi kosong dari flow aplikasi, index memadai di kolom yang sering di-filter, penamaan database/user tenant konsisten dengan batasan panjang identifier cPanel provider. |
| **Separation of Concern (20)** | Policy benar-benar dipanggil (bukan dead code), setiap layer (Controller/Service/Repository/Action) konsisten dipakai sesuai tanggung jawabnya. |
| **Code Quality (15)** | Tidak ada pola `$request->all()` ke `create()`, `env()` dipanggil lewat `config()` bukan langsung di controller. |
| **API Design (5)** | Satu endpoint per fungsi (tidak ada duplikasi legacy vs v1), rate limit di semua endpoint publik, response envelope konsisten. |

---

## 12. Urutan Prioritas Kalau Waktu Terbatas

Kalau resource terbatas dan tidak bisa mengerjakan semua fase sekaligus, urutan dampak-terhadap-risiko dari yang paling mendesak:

1. **Fase 0** (rotasi kredensial) — tidak bisa ditunda, risiko aktif sejak sekarang.
2. **5.2** (fix provisioning via cPanel UAPI) — fitur inti model bisnis tidak jalan sama sekali tanpa ini, dan pendekatan lama tidak akan pernah berfungsi di shared hosting.
3. **5.3** (hardcoded secret) — perbaikan cepat, dampak besar.
4. **5.4** (siapkan opsi fallback pool database) — kerjakan sebagai *contingency plan*, bahkan kalau UAPI berhasil di awal, supaya ada jalan keluar cepat kalau providermu berubah kebijakan API di kemudian hari.
5. Sisanya bisa dikerjakan paralel sesuai kapasitas tim.

---

*Dokumen ini dihasilkan dari audit `/laravel-supreme-analyzer-generator` terhadap `cooca-id.zip`. Semua referensi file & baris kode merujuk pada snapshot yang diunggah pada 11 Agustus 2026 — verifikasi ulang nomor baris kalau file sudah berubah sejak saat itu.*
