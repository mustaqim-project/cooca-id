@extends('layouts.customer')

@section('title', 'AI Gateway & API Keys — COOCA.ID')

@section('breadcrumb')
    <a href="{{ route('customer.dashboard') }}" class="crumb">Dashboard</a>
    <span class="crumb-separator">/</span>
    <span class="crumb-current">AI Platform & API Keys</span>
@endsection

@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <div>
        <div class="breadcrumb" style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">
            <a href="{{ route('customer.dashboard') }}" style="color: var(--primary); text-decoration: none;">Dashboard</a>
            <span>/</span>
            <span>AI Platform</span>
        </div>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--text); margin: 0; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-brain" style="color: var(--primary);"></i> AI Gateway & API Access
        </h1>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 14px;">
            Akses multi-model AI (OpenAI GPT, Google Gemini, Anthropic Claude, DeepSeek) menggunakan satu API Key terpadu.
        </p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        @if($licenses->isNotEmpty())
            <button type="button" class="btn btn-primary" onclick="openNewKeyModal()">
                <i class="fa-solid fa-plus"></i> Buat API Key Baru
            </button>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif

{{-- One-Time Reveal Flash Card --}}
@if(session('new_api_key'))
    <div class="card mb-4" style="border: 2px solid var(--success); background: var(--success-soft);">
        <div class="card-body" style="padding: 20px;">
            <div class="flex items-center gap-2 mb-2" style="color: var(--success); font-weight: 800; font-size: 15px;">
                <i class="fa-solid fa-key"></i> Simpan API Key Anda Sekarang!
            </div>
            <p class="text-xs text-muted mb-3" style="color: var(--text);">
                Demi keamanan akun Anda, kunci API rahasia ini <strong>hanya ditampilkan satu kali</strong> dan tidak dapat dilihat kembali setelah halaman ditutup.
            </p>
            <div class="flex items-center gap-2" style="flex-wrap: wrap;">
                <code id="new-plain-key" style="font-family: monospace; font-size: 14px; font-weight: 700; background: var(--card); color: var(--text); padding: 10px 16px; border-radius: var(--radius-sm); border: 1px solid var(--border); flex: 1; min-width: 280px; word-break: break-all;">{{ session('new_api_key')['plain_key'] }}</code>
                <button type="button" class="btn btn-primary" onclick="copyNewKey()" style="padding: 10px 18px;">
                    <i class="fa-solid fa-copy"></i> Salin API Key
                </button>
            </div>
        </div>
    </div>
@endif

{{-- Active License & AI Token Quotas --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px; margin-bottom: 24px;">
    @forelse($licenses as $lic)
        @php
            $cycle = $cycles->get($lic->id);
            $tokensUsed = $cycle ? $cycle->tokens_used : 0;
            $tokenQuota = $cycle ? $cycle->token_quota : 100000;
            $pct = $tokenQuota > 0 ? ($tokensUsed / $tokenQuota) * 100 : 0;
            $barColor = $pct > 90 ? 'var(--danger)' : ($pct > 75 ? 'var(--warning)' : 'var(--success)');
        @endphp
        <div class="card" style="border: 1px solid var(--border); box-shadow: var(--shadow-xs);">
            <div class="card-body" style="padding: 20px;">
                <div class="flex items-center justify-between mb-2">
                    <span class="badge badge-primary font-bold text-xs">
                        {{ $lic->product->name ?? 'Paket SaaS' }}
                    </span>
                    <span class="text-xs font-mono text-muted">
                        Lisensi: #{{ substr($lic->id, 0, 8) }}
                    </span>
                </div>

                <div class="text-xs text-muted mb-3">
                    Domain: <strong>{{ $lic->domain ?? 'Semua Domain' }}</strong>
                </div>

                <div class="mb-3">
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span style="color: var(--text);">Pemakaian Token Siklus Ini</span>
                        <span style="color: {{ $barColor }};">{{ number_format($pct, 1) }}%</span>
                    </div>
                    <div style="height: 8px; background: var(--bg-secondary); border-radius: 6px; overflow: hidden; border: 1px solid var(--border);">
                        <div style="height: 100%; width: {{ min(100, $pct) }}%; background: {{ $barColor }}; border-radius: 6px; transition: width 0.3s ease;"></div>
                    </div>
                    <div class="flex justify-between text-xs text-muted mt-1 font-mono">
                        <span>{{ number_format($tokensUsed) }} terpakai</span>
                        <span>{{ number_format($tokenQuota) }} kuota</span>
                    </div>
                </div>

                <div class="text-xs text-muted font-mono" style="border-top: 1px solid var(--border); padding-top: 10px;">
                    <i class="fa-regular fa-clock" style="margin-right: 4px;"></i> Reset Kuota: 
                    <strong>{{ $cycle?->cycle_end ? $cycle->cycle_end->format('d M Y') : 'Akhir Bulan' }}</strong>
                </div>
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card-body text-center text-muted" style="padding: 40px;">
                <i class="fa-solid fa-brain" style="font-size: 40px; color: var(--primary); opacity: 0.4; margin-bottom: 12px; display: block;"></i>
                <div class="font-bold text-base" style="color: var(--text);">Belum Ada Lisensi SaaS Aktif</div>
                <div class="text-xs text-muted mt-1">Berlangganan salah satu paket SaaS untuk mendapatkan akses AI Gateway & kuota token bulanan.</div>
            </div>
        </div>
    @endforelse
</div>

{{-- API Keys Table --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
        <div class="card-title font-bold text-sm" style="color: var(--text);">
            <i class="fa-solid fa-key" style="color: var(--primary); margin-right: 6px;"></i> Kunci API AI (API Keys)
        </div>
        @if($licenses->isNotEmpty())
            <button type="button" class="btn btn-outline btn-xs" onclick="openNewKeyModal()">
                <i class="fa-solid fa-plus"></i> Tambah Key
            </button>
        @endif
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--bg-secondary); border-bottom: 1px solid var(--border); font-size: 11px; text-transform: uppercase; color: var(--text-muted);">
                        <th style="padding: 12px 18px;">Nama Kunci</th>
                        <th style="padding: 12px 18px;">Prefix Kunci</th>
                        <th style="padding: 12px 18px;">Lisensi Terkait</th>
                        <th style="padding: 12px 18px;">Status</th>
                        <th style="padding: 12px 18px;">Terakhir Digunakan</th>
                        <th style="padding: 12px 18px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($keys as $k)
                        <tr style="border-bottom: 1px solid var(--border); font-size: 13px;">
                            <td style="padding: 12px 18px; font-weight: 700; color: var(--text);">
                                {{ $k->name }}
                            </td>
                            <td style="padding: 12px 18px;">
                                <code style="background: var(--bg-secondary); color: var(--text); padding: 4px 8px; border-radius: var(--radius-sm); font-size: 12px; font-family: monospace;">
                                    {{ $k->key_prefix }}••••••••••••••••
                                </code>
                            </td>
                            <td style="padding: 12px 18px;">
                                <span class="text-xs font-semibold" style="color: var(--primary);">
                                    {{ $k->license->product->name ?? 'Paket SaaS' }}
                                </span>
                            </td>
                            <td style="padding: 12px 18px;">
                                @if($k->status === 'active')
                                    <span class="badge badge-success" style="font-size: 11px;">Aktif</span>
                                @else
                                    <span class="badge badge-danger" style="font-size: 11px;">Revoked</span>
                                @endif
                            </td>
                            <td style="padding: 12px 18px; color: var(--text-muted); font-size: 12px;">
                                {{ $k->last_used_at ? $k->last_used_at->diffForHumans() : 'Belum pernah' }}
                            </td>
                            <td style="padding: 12px 18px; text-align: right;">
                                @if($k->status === 'active')
                                    <form action="{{ \Illuminate\Support\Facades\Route::has('customer.ai-usage.keys.revoke') ? route('customer.ai-usage.keys.revoke', $k->id) : url('/customer/ai-usage/keys/' . $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan API Key [{{ $k->name }}]? Aplikasi yang menggunakan kunci ini tidak akan bisa mengakses AI lagi.');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-xs" style="color: var(--danger); border-color: var(--border);">
                                            <i class="fa-solid fa-ban"></i> Revoke
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                Belum ada API Key yang dibuat. Klik tombol <strong>Buat API Key Baru</strong> di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Interactive API Documentation & Code Snippets --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
        <div class="card-title font-bold text-sm" style="color: var(--text);">
            <i class="fa-solid fa-code" style="color: var(--primary); margin-right: 6px;"></i> Panduan Integrasi Endpoint API
        </div>
        <span class="badge badge-primary text-xs">OpenAI Compatible Format</span>
    </div>
    <div class="card-body" style="padding: 20px;">
        <div class="flex items-center gap-2 mb-3">
            <span class="badge badge-success font-mono font-bold">POST</span>
            <code style="font-size: 13px; font-weight: 700; color: var(--text); background: var(--bg-secondary); padding: 6px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); flex: 1;">
                https://cooca.id/api/v1/ai/chat/completions
            </code>
        </div>

        <div style="margin-top: 14px;">
            <div class="flex items-center gap-2 mb-2">
                <button type="button" class="btn btn-primary btn-xs tab-btn active" onclick="switchSnippet('curl', this)">cURL</button>
                <button type="button" class="btn btn-outline btn-xs tab-btn" onclick="switchSnippet('js', this)">JavaScript (Node / Fetch)</button>
                <button type="button" class="btn btn-outline btn-xs tab-btn" onclick="switchSnippet('python', this)">Python</button>
                <button type="button" class="btn btn-outline btn-xs tab-btn" onclick="switchSnippet('php', this)">PHP (Laravel / Guzzle)</button>
            </div>

            <pre id="snippet-curl" style="background: var(--bg-secondary); color: var(--text); padding: 14px 16px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-family: monospace; font-size: 12px; line-height: 1.5; overflow-x: auto; margin: 0;">curl -X POST https://cooca.id/api/v1/ai/chat/completions \
  -H "Authorization: Bearer YOUR_COOCA_AI_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-4o-mini",
    "messages": [
      {"role": "system", "content": "You are a helpful assistant."},
      {"role": "user", "content": "Halo COOCA AI!"}
    ],
    "temperature": 0.7,
    "max_tokens": 1000
  }'</pre>

            <pre id="snippet-js" style="display: none; background: var(--bg-secondary); color: var(--text); padding: 14px 16px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-family: monospace; font-size: 12px; line-height: 1.5; overflow-x: auto; margin: 0;">const response = await fetch('https://cooca.id/api/v1/ai/chat/completions', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_COOCA_AI_KEY',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    model: 'gemini-1.5-flash',
    messages: [
      { role: 'user', content: 'Halo COOCA AI!' }
    ]
  })
});
const data = await response.json();
console.log(data.choices[0].message.content);</pre>

            <pre id="snippet-python" style="display: none; background: var(--bg-secondary); color: var(--text); padding: 14px 16px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-family: monospace; font-size: 12px; line-height: 1.5; overflow-x: auto; margin: 0;">from openai import OpenAI

client = OpenAI(
    api_key="YOUR_COOCA_AI_KEY",
    base_url="https://cooca.id/api/v1/ai"
)

response = client.chat.completions.create(
    model="deepseek-chat",
    messages=[{"role": "user", "content": "Halo COOCA AI!"}]
)
print(response.choices[0].message.content)</pre>

            <pre id="snippet-php" style="display: none; background: var(--bg-secondary); color: var(--text); padding: 14px 16px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-family: monospace; font-size: 12px; line-height: 1.5; overflow-x: auto; margin: 0;">use Illuminate\Support\Facades\Http;

$response = Http::withHeaders([
    'Authorization' => 'Bearer YOUR_COOCA_AI_KEY',
    'Content-Type'  => 'application/json',
])->post('https://cooca.id/api/v1/ai/chat/completions', [
    'model' => 'claude-3-5-haiku-20241022',
    'messages' => [
        ['role' => 'user', 'content' => 'Halo COOCA AI!'],
    ],
]);

$data = $response->json();
echo $data['choices'][0]['message']['content'];</pre>
        </div>
    </div>
</div>

{{-- Recent Requests Telemetry --}}
<div class="card">
    <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
        <div class="card-title font-bold text-sm" style="color: var(--text);">
            <i class="fa-solid fa-clock-rotate-left" style="color: var(--primary); margin-right: 6px;"></i> Riwayat Permintaan AI Terbaru
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--bg-secondary); border-bottom: 1px solid var(--border); font-size: 11px; text-transform: uppercase; color: var(--text-muted);">
                        <th style="padding: 12px 18px;">Waktu</th>
                        <th style="padding: 12px 18px;">Model</th>
                        <th style="padding: 12px 18px;">Prompt Tokens</th>
                        <th style="padding: 12px 18px;">Completion</th>
                        <th style="padding: 12px 18px;">Total Tokens</th>
                        <th style="padding: 12px 18px;">Latency</th>
                        <th style="padding: 12px 18px; text-align: right;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs as $log)
                        <tr style="border-bottom: 1px solid var(--border); font-size: 12px;">
                            <td style="padding: 12px 18px; color: var(--text-muted); font-family: monospace;">
                                {{ $log->created_at?->format('H:i:s') }}
                                <span style="font-size: 10px; display: block;">{{ $log->created_at?->format('d M Y') }}</span>
                            </td>
                            <td style="padding: 12px 18px;">
                                <code style="background: var(--primary-soft); color: var(--primary); padding: 3px 6px; border-radius: var(--radius-sm); font-weight: 700;">
                                    {{ $log->model }}
                                </code>
                            </td>
                            <td style="padding: 12px 18px; font-family: monospace;">{{ number_format($log->prompt_tokens) }}</td>
                            <td style="padding: 12px 18px; font-family: monospace;">{{ number_format($log->completion_tokens) }}</td>
                            <td style="padding: 12px 18px; font-family: monospace; font-weight: 700; color: var(--text);">{{ number_format($log->total_tokens) }}</td>
                            <td style="padding: 12px 18px; font-family: monospace; color: var(--text-muted);">{{ $log->duration_ms }} ms</td>
                            <td style="padding: 12px 18px; text-align: right;">
                                @if($log->status === 'success')
                                    <span class="badge badge-success" style="font-size: 10px;">200 OK</span>
                                @elseif($log->status === 'quota_exceeded')
                                    <span class="badge badge-warning" style="font-size: 10px;">429 QUOTA</span>
                                @else
                                    <span class="badge badge-danger" style="font-size: 10px;">{{ strtoupper($log->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding: 40px;">
                                Belum ada riwayat permintaan API AI.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal: Create API Key --}}
<div id="new-key-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 460px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md);">
        <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
            <div class="card-title font-bold" style="color: var(--text);">Buat AI API Key Baru</div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closeNewKeyModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ \Illuminate\Support\Facades\Route::has('customer.ai-usage.keys.store') ? route('customer.ai-usage.keys.store') : url('/customer/ai-usage/keys') }}" method="POST">
            @csrf
            <div class="card-body" style="display: flex; flex-direction: column; gap: 14px; padding: 20px;">
                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Pilih Lisensi SaaS</label>
                    <select name="license_id" required class="form-select" style="width: 100%;">
                        @foreach($licenses as $lic)
                            <option value="{{ $lic->id }}">
                                {{ $lic->product->name ?? 'Paket SaaS' }} ({{ $lic->domain ?? 'Semua Domain' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Nama Identifikasi Kunci</label>
                    <input type="text" name="name" required class="form-input" placeholder="contoh: Production ERP Chatbot, Staging API..." style="width: 100%;">
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px; border-top: 1px solid var(--border);">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeNewKeyModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-key"></i> Generate API Key</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openNewKeyModal() {
    document.getElementById('new-key-modal').style.display = 'flex';
}

function closeNewKeyModal() {
    document.getElementById('new-key-modal').style.display = 'none';
}

function copyNewKey() {
    const el = document.getElementById('new-plain-key');
    if (!el) return;
    navigator.clipboard.writeText(el.innerText).then(() => {
        alert('API Key berhasil disalin ke clipboard!');
    });
}

function switchSnippet(lang, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('btn-primary', 'active');
        b.classList.add('btn-outline');
    });
    btn.classList.add('btn-primary', 'active');
    btn.classList.remove('btn-outline');

    const langs = ['curl', 'js', 'python', 'php'];
    langs.forEach(l => {
        const el = document.getElementById('snippet-' + l);
        if (el) el.style.display = (l === lang) ? 'block' : 'none';
    });
}
</script>
@endpush
