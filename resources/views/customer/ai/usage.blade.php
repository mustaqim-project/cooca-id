@extends('layouts.customer')

@section('title', 'AI Gateway, Kuota & Tracking Penggunaan Token — COOCA.ID')

@push('styles')
<style>
.gateway-param-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}
.gateway-code-block {
    position: relative;
    background: #090d16;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 16px;
    font-family: 'JetBrains Mono', 'Fira Code', monospace;
    font-size: 12px;
    color: #e2e8f0;
    overflow-x: auto;
}
.gateway-token-badge {
    background: var(--primary-soft);
    color: var(--primary);
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 700;
    font-family: monospace;
}
.package-card {
    border: 2px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--card);
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
}
.package-card:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}
.package-card.selected {
    border-color: var(--primary);
    background: var(--primary-soft);
}
</style>
@endpush

@section('content')
<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-brain" style="color: var(--primary);"></i> AI Gateway & Tracking Token
        </h1>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 14px;">
            Pantau penggunaan token, sisa kuota, riwayat pemanggilan API, serta akses multi-model AI (OpenAI GPT, Gemini, Claude, DeepSeek).
        </p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        @if($licenses->isNotEmpty())
            <button type="button" class="btn btn-outline" onclick="openTopUpModal()" style="border-color: var(--primary); color: var(--primary); font-weight: 700;">
                <i class="fa-solid fa-bolt" style="color: var(--accent);"></i> Top-Up Kuota Token
            </button>
            <button type="button" class="btn btn-primary" onclick="openNewKeyModal()">
                <i class="fa-solid fa-plus"></i> Buat API Key Baru
            </button>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
@endif

{{-- Monthly KPI Cards for Customer --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="border: 1px solid var(--border); padding: 18px; border-radius: var(--radius-md);">
        <div class="flex justify-between items-center mb-2">
            <span class="text-xs font-bold uppercase text-muted">Token Terpakai Bulan Ini</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--primary-soft); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 14px;">
                <i class="fa-solid fa-microchip"></i>
            </div>
        </div>
        <div class="font-extrabold text-2xl" style="color: var(--text);">
            {{ number_format($currentMonthUsage->total_tokens ?? 0) }}
        </div>
        <div class="text-xs text-muted mt-1">Akumulasi input & output token</div>
    </div>

    <div class="card" style="border: 1px solid var(--border); padding: 18px; border-radius: var(--radius-md);">
        <div class="flex justify-between items-center mb-2">
            <span class="text-xs font-bold uppercase text-muted">Total Permintaan API</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--success-soft); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 14px;">
                <i class="fa-solid fa-bolt"></i>
            </div>
        </div>
        <div class="font-extrabold text-2xl" style="color: var(--success);">
            {{ number_format($currentMonthUsage->total_requests ?? 0) }}
        </div>
        <div class="text-xs text-muted mt-1">Panggilan chat/completions</div>
    </div>

    <div class="card" style="border: 1px solid var(--border); padding: 18px; border-radius: var(--radius-md);">
        <div class="flex justify-between items-center mb-2">
            <span class="text-xs font-bold uppercase text-muted">Rata-rata Waktu Respon</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--warning-soft); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 14px;">
                <i class="fa-solid fa-stopwatch"></i>
            </div>
        </div>
        <div class="font-extrabold text-2xl" style="color: var(--warning);">
            {{ number_format((float)($currentMonthUsage->avg_latency ?? 0)) }} ms
        </div>
        <div class="text-xs text-muted mt-1">Kecepatan latensi gateway</div>
    </div>

    <div class="card" style="border: 1px solid var(--border); padding: 18px; border-radius: var(--radius-md);">
        <div class="flex justify-between items-center mb-2">
            <span class="text-xs font-bold uppercase text-muted">Status AI Gateway</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--accent-soft); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 14px;">
                <i class="fa-solid fa-shield-check"></i>
            </div>
        </div>
        <div class="font-extrabold text-2xl" style="color: var(--accent);">
            Aktif & Siap
        </div>
        <div class="text-xs text-muted mt-1">Protokol OpenAI 100% Kompatibel</div>
    </div>
</div>

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
                    <button type="button" class="btn btn-ghost btn-xs" onclick="openTopUpModal('{{ $lic->id }}')" style="color: var(--primary); font-weight: 700;">
                        <i class="fa-solid fa-bolt"></i> + Top-Up Kuota
                    </button>
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

                <div class="text-xs text-muted font-mono" style="border-top: 1px solid var(--border); padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        <i class="fa-regular fa-clock" style="margin-right: 4px;"></i> Reset: 
                        <strong>{{ $cycle?->cycle_end ? $cycle->cycle_end->format('d M Y') : 'Akhir Bulan' }}</strong>
                    </span>
                    <span class="text-xs font-mono text-muted">Lic: {{ substr($lic->id, 0, 8) }}...</span>
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
                        <th style="padding: 12px 18px;">API Key Rahasia</th>
                        <th style="padding: 12px 18px;">Lisensi Terkait</th>
                        <th style="padding: 12px 18px;">Status</th>
                        <th style="padding: 12px 18px;">Terakhir Digunakan</th>
                        <th style="padding: 12px 18px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($keys as $k)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 14px 18px;">
                                <strong style="color: var(--text); font-size: 13px;">{{ $k->name }}</strong>
                                <div class="text-xs text-muted font-mono">{{ $k->id }}</div>
                            </td>
                            <td style="padding: 14px 18px;">
                                @php
                                    $hasPlain = !empty($k->plain_key);
                                    $masked = $k->key_prefix . '••••••••••••••••••••••••';
                                    $full = $hasPlain ? $k->plain_key : $masked;
                                @endphp
                                <div class="flex items-center gap-2" style="max-width: 320px;">
                                    <code id="key-text-{{ $k->id }}" 
                                          data-full="{{ $full }}" 
                                          data-masked="{{ $masked }}" 
                                          data-state="masked"
                                          class="font-mono text-xs font-bold" 
                                          style="background: var(--bg-secondary); color: var(--text); padding: 4px 8px; border-radius: 4px; border: 1px solid var(--border); word-break: break-all; flex: 1;">
                                        {{ $masked }}
                                    </code>
                                    
                                    @if($hasPlain)
                                        <button type="button" class="btn btn-ghost btn-xs" onclick="toggleKeyVisibility('{{ $k->id }}')" title="Tampilkan / Sembunyikan Kunci" style="padding: 4px 6px;">
                                            <i id="key-eye-{{ $k->id }}" class="fa-regular fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-ghost btn-xs" onclick="copyKeyText('{{ $k->id }}')" title="Salin API Key" style="padding: 4px 6px;">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 14px 18px;">
                                <span class="font-bold text-xs" style="color: var(--text);">
                                    {{ $k->license->product->name ?? 'Paket SaaS' }}
                                </span>
                                <div class="text-xs text-muted font-mono">
                                    {{ $k->license->domain ?? 'Semua Domain' }}
                                </div>
                            </td>
                            <td style="padding: 14px 18px;">
                                @if($k->status === 'active')
                                    <span class="badge badge-success" style="font-size: 10px;">AKTIF</span>
                                @else
                                    <span class="badge badge-danger" style="font-size: 10px;">REVOKED</span>
                                @endif
                            </td>
                            <td style="padding: 14px 18px;" class="font-mono text-xs text-muted">
                                {{ $k->last_used_at ? $k->last_used_at->diffForHumans() : 'Belum pernah' }}
                            </td>
                            <td style="padding: 14px 18px; text-align: right;">
                                @if($k->status === 'active')
                                    <form action="{{ \Illuminate\Support\Facades\Route::has('customer.ai-usage.keys.revoke') ? route('customer.ai-usage.keys.revoke', $k->id) : url('/customer/ai-usage/keys/' . $k->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menonaktifkan API Key ini? Aksi ini tidak dapat dibatalkan.');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs" style="color: var(--danger);" title="Revoke Key">
                                            <i class="fa-solid fa-ban"></i> Nonaktifkan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                Belum ada AI API Key. Buat kunci pertama Anda untuk mulai memanggil API.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Live Token Tracking & Telemetry Logs --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
        <div class="card-title font-bold text-sm" style="color: var(--text);">
            <i class="fa-solid fa-list-check" style="color: var(--primary); margin-right: 6px;"></i> Live Tracking & Riwayat Penggunaan Token
        </div>
        <span class="text-xs text-muted">Pencatatan real-time setiap request AI</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--bg-secondary); border-bottom: 1px solid var(--border); font-size: 11px; text-transform: uppercase; color: var(--text-muted);">
                        <th style="padding: 12px 18px;">Waktu</th>
                        <th style="padding: 12px 18px;">API Key</th>
                        <th style="padding: 12px 18px;">Model LLM</th>
                        <th style="padding: 12px 18px;">Prompt (Input)</th>
                        <th style="padding: 12px 18px;">Completion (Output)</th>
                        <th style="padding: 12px 18px;">Total Token</th>
                        <th style="padding: 12px 18px;">Latensi</th>
                        <th style="padding: 12px 18px; text-align: right;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs as $log)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 14px 18px;" class="font-mono text-xs text-muted">
                                {{ $log->created_at?->format('d M Y') }}
                                <div style="font-size: 11px; color: var(--text);">{{ $log->created_at?->format('H:i:s') }}</div>
                            </td>
                            <td style="padding: 14px 18px;">
                                <div class="font-bold text-xs" style="color: var(--text);">{{ $log->apiKey->name ?? 'API Key' }}</div>
                                <div class="font-mono text-xs text-muted">{{ substr($log->ai_api_key_id, 0, 8) }}...</div>
                            </td>
                            <td style="padding: 14px 18px;">
                                <code style="background: var(--primary-soft); color: var(--primary); padding: 3px 6px; border-radius: 4px; font-weight: 700; font-size: 11px;">
                                    {{ $log->model }}
                                </code>
                            </td>
                            <td style="padding: 14px 18px;" class="font-mono text-xs">{{ number_format($log->prompt_tokens) }}</td>
                            <td style="padding: 14px 18px;" class="font-mono text-xs">{{ number_format($log->completion_tokens) }}</td>
                            <td style="padding: 14px 18px;" class="font-mono text-xs font-bold" style="color: var(--text);">
                                <span class="badge badge-primary font-mono text-xs">{{ number_format($log->total_tokens) }}</span>
                            </td>
                            <td style="padding: 14px 18px;" class="font-mono text-xs text-muted">{{ $log->duration_ms }} ms</td>
                            <td style="padding: 14px 18px; text-align: right;">
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
                            <td colspan="8" class="text-center text-muted" style="padding: 40px;">
                                Belum ada riwayat panggilan API atau penggunaan token yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($recentLogs->hasPages())
        <div class="card-footer" style="padding: 14px 20px; border-top: 1px solid var(--border);">
            {{ $recentLogs->links() }}
        </div>
    @endif
</div>

{{-- Quick Integration Guide --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
        <div class="card-title font-bold text-sm" style="color: var(--text);">
            <i class="fa-solid fa-code" style="color: var(--primary); margin-right: 6px;"></i> Panduan Integrasi (OpenAI-Compatible SDK & cURL)
        </div>
        <span class="text-xs text-muted">Base URL: <code style="color: var(--primary); font-weight: bold;">https://cooca.id/api/v1/ai</code></span>
    </div>
    <div class="card-body" style="padding: 20px;">
        <p class="text-xs text-muted mb-3" style="color: var(--text-muted);">
            COOCA AI Gateway 100% kompatibel dengan protokol format OpenAI. Cukup ubah <code>baseURL</code> pada library resmi OpenAI (Node.js, Python, PHP, Go) atau kirim cURL langsung.
        </p>

        <div class="gateway-code-block mb-3">
<pre style="margin: 0;"><code>curl -X POST https://cooca.id/api/v1/ai/chat/completions \
  -H "Authorization: Bearer YOUR_COOCA_AI_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gemini-3.6-flash",
    "messages": [
      {"role": "system", "content": "You are a helpful assistant."},
      {"role": "user", "content": "Halo COOCA AI!"}
    ],
    "temperature": 0.7,
    "max_tokens": 1000
  }'</code></pre>
        </div>
    </div>
</div>

{{-- Modal: Top-Up Token AI --}}
<div id="topup-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 680px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md); max-height: 90vh; overflow-y: auto;">
        <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
            <div class="card-title font-bold" style="color: var(--text); display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-bolt" style="color: var(--accent);"></i> Top-Up Kuota Token AI
            </div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closeTopUpModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('customer.ai-usage.packages.purchase') }}" method="POST">
            @csrf
            <div class="card-body" style="display: flex; flex-direction: column; gap: 18px; padding: 20px;">
                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">1. Pilih Lisensi SaaS yang Akan Diisi</label>
                    <select name="license_id" id="topup-license-select" required class="form-select" style="width: 100%;">
                        @foreach($licenses as $lic)
                            <option value="{{ $lic->id }}">
                                {{ $lic->product->name ?? 'Paket SaaS' }} — Lisensi: #{{ substr($lic->id, 0, 8) }} ({{ $lic->domain ?? 'Semua Domain' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label text-xs font-bold uppercase mb-2" style="display: block;">2. Pilih Paket Kuota Token</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px;">
                        @forelse($tokenPackages as $idx => $pkg)
                            <label class="package-card {{ $idx === 0 ? 'selected' : '' }}" onclick="selectPackageRadio('pkg-{{ $pkg->id }}', this)">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold text-sm" style="color: var(--text);">{{ $pkg->name }}</div>
                                    @if($pkg->badge)
                                        <span class="badge badge-warning" style="font-size: 10px; font-weight: 700;">{{ $pkg->badge }}</span>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <div class="font-mono font-extrabold text-base" style="color: var(--primary);">
                                        +{{ number_format($pkg->token_amount) }} Token
                                    </div>
                                    <div class="text-xs text-muted mt-1">{{ $pkg->description ?? 'Tambahan kuota token instan' }}</div>
                                </div>
                                <div class="flex justify-between items-center pt-2" style="border-top: 1px solid var(--border); margin-top: auto;">
                                    <span class="font-extrabold text-sm" style="color: var(--text);">
                                        Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                    </span>
                                    <input type="radio" name="package_id" id="pkg-{{ $pkg->id }}" value="{{ $pkg->id }}" {{ $idx === 0 ? 'checked' : '' }} required style="accent-color: var(--primary);">
                                </div>
                            </label>
                        @empty
                            <div class="text-center text-muted" style="padding: 20px; grid-column: 1 / -1;">
                                Belum ada paket top-up yang aktif saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card" style="background: var(--bg-secondary); border: 1px solid var(--border); padding: 14px; border-radius: var(--radius-sm);">
                    <div class="flex items-center gap-2 text-xs font-semibold" style="color: var(--text);">
                        <i class="fa-solid fa-shield-halved" style="color: var(--success);"></i> Pembayaran Aman & Instan
                    </div>
                    <p class="text-xs text-muted mb-0 mt-1">
                        Mendukung pembayaran otomatis melalui <strong>QRIS, Virtual Account BCA/Mandiri/BNI/BRI</strong> via Midtrans atau <strong>Transfer Bank Manual</strong>. Kuota otomatis bertambah segera setelah pembayaran berstatus Lunas.
                    </p>
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px; border-top: 1px solid var(--border);">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeTopUpModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-cart-shopping"></i> Lanjutkan Pembayaran</button>
            </div>
        </form>
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
function openTopUpModal(licenseId = null) {
    if (licenseId) {
        const select = document.getElementById('topup-license-select');
        if (select) select.value = licenseId;
    }
    document.getElementById('topup-modal').style.display = 'flex';
}

function closeTopUpModal() {
    document.getElementById('topup-modal').style.display = 'none';
}

function selectPackageRadio(radioId, cardEl) {
    const radio = document.getElementById(radioId);
    if (radio) radio.checked = true;

    document.querySelectorAll('.package-card').forEach(c => c.classList.remove('selected'));
    cardEl.classList.add('selected');
}

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

function toggleKeyVisibility(id) {
    const codeEl = document.getElementById('key-text-' + id);
    const eyeEl = document.getElementById('key-eye-' + id);
    if (!codeEl || !eyeEl) return;

    const fullKey = codeEl.getAttribute('data-full');
    const maskedKey = codeEl.getAttribute('data-masked');
    const state = codeEl.getAttribute('data-state');

    if (state === 'masked') {
        codeEl.innerText = fullKey;
        codeEl.setAttribute('data-state', 'revealed');
        eyeEl.className = 'fa-regular fa-eye-slash';
    } else {
        codeEl.innerText = maskedKey;
        codeEl.setAttribute('data-state', 'masked');
        eyeEl.className = 'fa-regular fa-eye';
    }
}

function copyKeyText(id) {
    const codeEl = document.getElementById('key-text-' + id);
    if (!codeEl) return;
    const fullKey = codeEl.getAttribute('data-full');
    navigator.clipboard.writeText(fullKey).then(() => {
        alert('API Key berhasil disalin ke clipboard!');
    });
}
</script>
@endpush
