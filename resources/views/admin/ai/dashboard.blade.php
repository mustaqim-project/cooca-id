@extends('layouts.admin')

@section('title', 'AI Gateway & Intelligence Console — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>AI Platform</span>
            <span>/</span>
            <span>Gateway & Models</span>
        </div>
        <h1 class="page-title">
            <i class="fa-solid fa-brain text-primary" style="margin-right: 6px;"></i> AI Gateway & Intelligence Console
        </h1>
        <p class="page-subtitle">Pusat kontrol multi-provider LLM (OpenAI, Anthropic Claude, Google Gemini, DeepSeek), kuota token SaaS, dan telemetri request.</p>
    </div>
    <div class="page-actions flex items-center gap-2">
        <button type="button" class="btn btn-outline btn-sm" onclick="window.location.reload();">
            <i class="fa-solid fa-arrows-rotate"></i> Refresh Telemetry
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

{{-- KPI Metrics Grid --}}
<div class="kpi-grid">
    <div class="kpi-card" style="--kpi-color1: var(--primary); --kpi-color2: var(--accent);">
        <div class="kpi-header">
            <span class="kpi-label">Tokens Bulan Ini</span>
            <div class="kpi-icon" style="background: var(--primary-soft); color: var(--primary);">
                <i class="fa-solid fa-microchip"></i>
            </div>
        </div>
        <div class="kpi-value">{{ number_format($monthlyUsage->total_tokens ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label">Konsumsi token seluruh customer</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--success); --kpi-color2: #059669;">
        <div class="kpi-header">
            <span class="kpi-label">Estimasi Biaya API</span>
            <div class="kpi-icon" style="background: var(--success-soft); color: var(--success);">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--success);">${{ number_format($monthlyUsage->total_cost_usd ?? 0, 4) }}</div>
        <div class="kpi-trend">
            <span class="trend-label text-success">Estimasi tagihan upstream provider</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--purple); --kpi-color2: var(--primary);">
        <div class="kpi-header">
            <span class="kpi-label">Total Permintaan AI</span>
            <div class="kpi-icon" style="background: var(--purple-soft); color: var(--purple);">
                <i class="fa-solid fa-bolt"></i>
            </div>
        </div>
        <div class="kpi-value">{{ number_format($monthlyUsage->total_requests ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label">Request chat completions</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--warning); --kpi-color2: var(--orange);">
        <div class="kpi-header">
            <span class="kpi-label">Rata-Rata Latensi</span>
            <div class="kpi-icon" style="background: var(--warning-soft); color: var(--warning);">
                <i class="fa-solid fa-stopwatch"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--warning);">{{ number_format($monthlyUsage->avg_latency_ms ?? 0) }} ms</div>
        <div class="kpi-trend">
            <span class="trend-label">Kecepatan respon gateway</span>
        </div>
    </div>
</div>

{{-- Section: AI Upstream Providers --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-server" style="color: var(--primary); margin-right: 6px;"></i> Upstream LLM Providers & API Keys
        </div>
        <span class="badge badge-primary text-xs font-bold">4 Engine Didukung</span>
    </div>
    <div class="card-body" style="padding: 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
            @php
                $providerMeta = [
                    'openai' => [
                        'title' => 'OpenAI',
                        'desc' => 'GPT-4o, GPT-4o Mini, o1, o3-mini',
                        'icon' => 'fa-solid fa-cube',
                        'color' => '#10a37f',
                    ],
                    'anthropic' => [
                        'title' => 'Anthropic Claude',
                        'desc' => 'Claude 3.5 Sonnet, Claude 3.5 Haiku',
                        'icon' => 'fa-solid fa-feather-pointed',
                        'color' => '#d97706',
                    ],
                    'gemini' => [
                        'title' => 'Google Gemini',
                        'desc' => 'Gemini 1.5 Flash, 1.5 Pro, 2.0 Flash',
                        'icon' => 'fa-brands fa-google',
                        'color' => '#4285f4',
                    ],
                    'deepseek' => [
                        'title' => 'DeepSeek AI',
                        'desc' => 'DeepSeek-V3, DeepSeek-R1 Reasoner',
                        'icon' => 'fa-solid fa-compass',
                        'color' => '#6366f1',
                    ],
                ];
            @endphp

            @foreach($providers as $pKey => $pData)
                @php
                    $meta = $providerMeta[$pKey] ?? ['title' => ucfirst($pKey), 'desc' => 'AI Engine', 'icon' => 'fa-solid fa-cube', 'color' => 'var(--primary)'];
                @endphp
                <div class="card" style="background: var(--bg-secondary); border: 1px solid var(--border); box-shadow: none; border-radius: var(--radius-sm); padding: 18px;">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: {{ $meta['color'] }}20; color: {{ $meta['color'] }}; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                <i class="{{ $meta['icon'] }}"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm" style="color: var(--text);">{{ $meta['title'] }}</div>
                                <div class="text-xs text-muted">{{ $meta['desc'] }}</div>
                            </div>
                        </div>

                        @if($pData['is_active'])
                            <span class="badge badge-success" style="font-weight: 700;">AKTIF</span>
                        @elseif($pData['is_configured'])
                            <span class="badge badge-muted">NONAKTIF</span>
                        @else
                            <span class="badge badge-warning">BELUM DISET</span>
                        @endif
                    </div>

                    <div class="text-xs text-muted mb-3 font-mono" style="word-break: break-all; margin-top: 8px;">
                        Base URL: <code style="color: var(--text-2);">{{ $pData['base_url'] }}</code>
                    </div>

                    <div class="flex items-center gap-2 mt-auto" style="flex-wrap: wrap; pt-2; border-top: 1px solid var(--border); padding-top: 12px;">
                        <button type="button" class="btn btn-outline btn-xs" onclick="openProviderModal('{{ $pKey }}', '{{ $meta['title'] }}', '{{ $pData['base_url'] }}', {{ $pData['is_active'] ? 'true' : 'false' }})">
                            <i class="fa-solid fa-gear"></i> Konfigurasi
                        </button>

                        @if($pData['is_configured'])
                            <form action="{{ \Illuminate\Support\Facades\Route::has('admin.ai.providers.toggle') ? route('admin.ai.providers.toggle') : url('/admin/ai/providers/toggle') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="provider" value="{{ $pKey }}">
                                <button type="submit" class="btn btn-ghost btn-xs" title="Toggle Status">
                                    <i class="fa-solid {{ $pData['is_active'] ? 'fa-toggle-on text-success' : 'fa-toggle-off text-muted' }}" style="font-size: 14px;"></i>
                                </button>
                            </form>

                            <form action="{{ \Illuminate\Support\Facades\Route::has('admin.ai.providers.test') ? route('admin.ai.providers.test') : url('/admin/ai/providers/test') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="provider" value="{{ $pKey }}">
                                <button type="submit" class="btn btn-outline btn-xs" title="Test Connection Ping">
                                    <i class="fa-solid fa-vial"></i> Test Ping
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Section: Active Usage Cycles & Quotas --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-chart-pie" style="color: var(--primary); margin-right: 6px;"></i> Siklus Penggunaan Token Aktif Customer
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Customer / Instansi</th>
                        <th>Paket / Lisensi</th>
                        <th>Periode Siklus</th>
                        <th style="min-width: 220px;">Pemakaian Token</th>
                        <th style="text-align: right; width: 140px;">Aksi Bonus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeCycles as $cycle)
                        @php
                            $pct = $cycle->token_quota > 0 ? ($cycle->tokens_used / $cycle->token_quota) * 100 : 0;
                            $barColor = $pct > 90 ? 'var(--danger)' : ($pct > 75 ? 'var(--warning)' : 'var(--success)');
                        @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-sm" style="color: var(--text);">
                                    {{ $cycle->license->customer->name ?? 'Customer' }}
                                </div>
                                <div class="text-xs text-muted">{{ $cycle->license->customer->email ?? '' }}</div>
                            </td>
                            <td>
                                <span class="font-bold text-xs" style="color: var(--primary);">
                                    {{ $cycle->license->product->name ?? 'Paket SaaS' }}
                                </span>
                                <div class="text-xs font-mono text-muted">Lic: {{ substr($cycle->license_id, 0, 8) }}...</div>
                            </td>
                            <td>
                                <div class="text-xs font-semibold" style="color: var(--text);">
                                    {{ $cycle->cycle_start?->format('d M Y') }} — {{ $cycle->cycle_end?->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span style="color: var(--text);">{{ number_format($cycle->tokens_used) }} terpakai</span>
                                    <span class="text-muted">Quota: {{ number_format($cycle->token_quota) }}</span>
                                </div>
                                <div style="height: 6px; background: var(--bg-secondary); border-radius: 4px; overflow: hidden; border: 1px solid var(--border);">
                                    <div style="height: 100%; width: {{ min(100, $pct) }}%; background: {{ $barColor }}; border-radius: 4px; transition: width 0.3s ease;"></div>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="btn btn-outline btn-xs" onclick="openBonusModal('{{ $cycle->id }}', '{{ $cycle->license->customer->name ?? 'Customer' }}')">
                                    <i class="fa-solid fa-gift" style="color: var(--primary);"></i> +Bonus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 40px;">
                                Belum ada siklus AI aktif saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($activeCycles->hasPages())
        <div class="card-footer" style="padding: 14px 20px;">
            {{ $activeCycles->links() }}
        </div>
    @endif
</div>

{{-- Section: Recent AI Telemetry Logs --}}
<div class="card">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-list-check" style="color: var(--primary); margin-right: 6px;"></i> Telemetri Permintaan AI Terbaru
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Customer / Key</th>
                        <th>Model LLM</th>
                        <th>Prompt Tokens</th>
                        <th>Completion</th>
                        <th>Total Tokens</th>
                        <th>Durasi (ms)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs as $log)
                        <tr>
                            <td class="font-mono text-xs text-muted">
                                {{ $log->created_at?->format('H:i:s') }}
                                <div style="font-size: 10px;">{{ $log->created_at?->format('d/m/y') }}</div>
                            </td>
                            <td>
                                <div class="font-bold text-xs" style="color: var(--text);">
                                    {{ $log->apiKey->customer->name ?? 'Customer' }}
                                </div>
                                <div class="font-mono text-xs text-muted">{{ $log->apiKey->name ?? 'API Key' }}</div>
                            </td>
                            <td>
                                <code style="background: var(--primary-soft); color: var(--primary); padding: 3px 6px; border-radius: 4px; font-weight: 700; font-size: 11px;">
                                    {{ $log->model }}
                                </code>
                            </td>
                            <td class="font-mono text-xs">{{ number_format($log->prompt_tokens) }}</td>
                            <td class="font-mono text-xs">{{ number_format($log->completion_tokens) }}</td>
                            <td class="font-mono text-xs font-bold" style="color: var(--text);">{{ number_format($log->total_tokens) }}</td>
                            <td class="font-mono text-xs text-muted">{{ $log->duration_ms }} ms</td>
                            <td>
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
                                Belum ada telemetri log AI yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal: Provider Config --}}
<div id="provider-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 480px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md);">
        <div class="card-header flex justify-between items-center">
            <div class="card-title" id="modal-provider-title">Konfigurasi Provider</div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closeProviderModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ \Illuminate\Support\Facades\Route::has('admin.ai.providers.save') ? route('admin.ai.providers.save') : url('/admin/ai/providers/save') }}" method="POST">
            @csrf
            <input type="hidden" name="provider" id="modal-provider-key" value="">
            <div class="card-body" style="display: flex; flex-direction: column; gap: 14px; padding: 20px;">
                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">API Key (Rahasia)</label>
                    <input type="password" name="api_key" class="form-input" placeholder="Masukkan Secret API Key (Kosongkan bila tidak diubah)...">
                    <small class="text-muted text-xs">Tersimpan dengan enkripsi standar AES-256 di database.</small>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Base URL Endpoint</label>
                    <input type="url" name="base_url" id="modal-provider-url" required class="form-input">
                </div>

                <div class="form-group flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="modal-provider-active" value="1" style="cursor: pointer;">
                    <label for="modal-provider-active" class="text-sm font-semibold" style="cursor: pointer; color: var(--text);">Aktifkan Provider Ini</label>
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px;">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeProviderModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk"></i> Simpan Konfigurasi</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Bonus Tokens --}}
<div id="bonus-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 440px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md);">
        <div class="card-header flex justify-between items-center">
            <div class="card-title">Tambah Bonus Kuota Token</div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closeBonusModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="bonus-form" action="" method="POST">
            @csrf
            <div class="card-body" style="display: flex; flex-direction: column; gap: 14px; padding: 20px;">
                <div class="text-sm" style="color: var(--text);">
                    Penerima: <strong id="bonus-customer-name"></strong>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Jumlah Token Bonus</label>
                    <input type="number" name="bonus_tokens" value="50000" step="5000" min="1000" required class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Alasan Pemberian Bonus</label>
                    <input type="text" name="reason" value="Promosi & Loyalty Reward" required class="form-input">
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px;">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeBonusModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-gift"></i> Berikan Bonus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openProviderModal(key, title, url, isActive) {
    document.getElementById('modal-provider-key').value = key;
    document.getElementById('modal-provider-title').innerText = 'Konfigurasi ' + title;
    document.getElementById('modal-provider-url').value = url;
    document.getElementById('modal-provider-active').checked = isActive;
    
    const modal = document.getElementById('provider-modal');
    modal.style.display = 'flex';
}

function closeProviderModal() {
    document.getElementById('provider-modal').style.display = 'none';
}

function openBonusModal(cycleId, customerName) {
    document.getElementById('bonus-form').action = '/admin/ai/cycles/' + cycleId + '/bonus';
    document.getElementById('bonus-customer-name').innerText = customerName;
    document.getElementById('bonus-modal').style.display = 'flex';
}

function closeBonusModal() {
    document.getElementById('bonus-modal').style.display = 'none';
}
</script>
@endpush
