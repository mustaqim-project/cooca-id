@extends('layouts.admin')

@section('title', 'AI Gateway & LLM Routing Console — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>AI Platform</span>
            <span>/</span>
            <span>Console</span>
        </div>
        <h1 class="page-title">
            <i class="fa-solid fa-brain text-primary" style="margin-right: 6px;"></i> AI Gateway & Model Routing Console
        </h1>
        <p class="page-subtitle">Pusat kontrol multi-provider AI (OpenAI, Anthropic, Gemini, DeepSeek), routing model LLM, kuota token, dan paket top-up.</p>
    </div>
    <div class="page-actions flex items-center gap-2" style="flex-wrap: wrap;">
        <button type="button" class="btn btn-outline btn-sm" onclick="openPackageModal()">
            <i class="fa-solid fa-plus"></i> Tambah Paket Top-Up
        </button>
        <div class="badge badge-success flex items-center gap-2" style="padding: 6px 12px; font-weight: 700;">
            <span style="width: 8px; height: 8px; background: currentColor; border-radius: 50%; display: inline-block;"></span>
            AI Gateway Live
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
@endif

{{-- Monthly KPI Cards --}}
<div class="kpi-grid">
    <div class="kpi-card" style="--kpi-color1: var(--primary); --kpi-color2: var(--accent);">
        <div class="kpi-header">
            <span class="kpi-label">Token Digunakan Bulan Ini</span>
            <div class="kpi-icon" style="background: var(--primary-soft); color: var(--primary);">
                <i class="fa-solid fa-microchip"></i>
            </div>
        </div>
        <div class="kpi-value">{{ number_format($monthlyUsage->total_tokens ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label">Akumulasi seluruh customer</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--success); --kpi-color2: #059669;">
        <div class="kpi-header">
            <span class="kpi-label">Total Permintaan API</span>
            <div class="kpi-icon" style="background: var(--success-soft); color: var(--success);">
                <i class="fa-solid fa-bolt"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--success);">{{ number_format($monthlyUsage->total_requests ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label text-success">API Calls ke Gateway</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--warning); --kpi-color2: #d97706;">
        <div class="kpi-header">
            <span class="kpi-label">Rata-rata Latensi</span>
            <div class="kpi-icon" style="background: var(--warning-soft); color: var(--warning);">
                <i class="fa-solid fa-stopwatch"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--warning);">{{ number_format($monthlyUsage->avg_latency ?? 0) }} ms</div>
        <div class="kpi-trend">
            <span class="trend-label">Rata-rata waktu respon model</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--accent); --kpi-color2: var(--primary);">
        <div class="kpi-header">
            <span class="kpi-label">Estimasi Biaya Provider</span>
            <div class="kpi-icon" style="background: var(--accent-soft); color: var(--accent);">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--accent);">${{ number_format((float)($monthlyUsage->total_cost ?? 0), 4) }}</div>
        <div class="kpi-trend">
            <span class="trend-label">Bulan berjalan (USD)</span>
        </div>
    </div>
</div>

{{-- Section: AI Provider Settings & Cards --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-server" style="color: var(--primary); margin-right: 6px;"></i> Provider LLM Terintegrasi
        </div>
        <span class="text-xs text-muted">Koneksi API Master Provider (Terenkripsi AES-256)</span>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
            @php
                $providerMeta = [
                    'openai' => ['title' => 'OpenAI', 'icon' => 'fa-brands fa-openid', 'color' => '#10A37F', 'models' => 'GPT-4o, GPT-4o Mini, O1'],
                    'anthropic' => ['title' => 'Anthropic Claude', 'icon' => 'fa-solid fa-brain', 'color' => '#D97706', 'models' => 'Claude 3.5 Sonnet, Claude 3.5 Haiku'],
                    'gemini' => ['title' => 'Google Gemini', 'icon' => 'fa-brands fa-google', 'color' => '#4285F4', 'models' => 'Gemini 3.6 Flash, Gemini 2.5 Pro'],
                    'deepseek' => ['title' => 'DeepSeek', 'icon' => 'fa-solid fa-compass', 'color' => '#0D9488', 'models' => 'DeepSeek-V3, DeepSeek-R1'],
                ];
            @endphp

            @foreach($providers as $pKey => $pData)
                @php $meta = $providerMeta[$pKey] ?? ['title' => ucfirst($pKey), 'icon' => 'fa-solid fa-robot', 'color' => 'var(--primary)', 'models' => 'Standard Models']; @endphp
                <div class="card" style="border: 1px solid var(--border); box-shadow: none; display: flex; flex-direction: column; padding: 18px; border-radius: var(--radius-sm);">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div style="width: 38px; height: 38px; border-radius: 8px; background: var(--bg-secondary); color: {{ $meta['color'] }}; display: flex; align-items: center; justify-content: center; font-size: 18px; border: 1px solid var(--border);">
                                <i class="{{ $meta['icon'] }}"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm" style="color: var(--text);">{{ $meta['title'] }}</div>
                                <div class="text-xs text-muted">{{ $meta['models'] }}</div>
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

{{-- Section: AI Token Top-Up Packages Management --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-cubes-stacked" style="color: var(--primary); margin-right: 6px;"></i> Paket Top-Up Kuota Token AI
        </div>
        <button type="button" class="btn btn-primary btn-xs" onclick="openPackageModal()">
            <i class="fa-solid fa-plus"></i> Tambah Paket
        </button>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th>Jumlah Kuota Token</th>
                        <th>Harga (IDR)</th>
                        <th>Badge Promo</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tokenPackages as $pkg)
                        <tr>
                            <td>
                                <div class="font-bold text-sm" style="color: var(--text);">{{ $pkg->name }}</div>
                                <div class="text-xs text-muted">{{ $pkg->description ?? 'Tidak ada deskripsi' }}</div>
                            </td>
                            <td>
                                <span class="font-mono font-bold text-sm" style="color: var(--primary);">
                                    +{{ number_format($pkg->token_amount) }} Token
                                </span>
                            </td>
                            <td>
                                <div class="font-bold text-sm" style="color: var(--text);">
                                    Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                </div>
                            </td>
                            <td>
                                @if($pkg->badge)
                                    <span class="badge badge-warning" style="font-size: 11px;">{{ $pkg->badge }}</span>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($pkg->is_active)
                                    <span class="badge badge-success" style="font-size: 10px;">AKTIF</span>
                                @else
                                    <span class="badge badge-muted" style="font-size: 10px;">NONAKTIF</span>
                                @endif
                            </td>
                            <td class="font-mono text-xs text-muted">#{{ $pkg->sort_order }}</td>
                            <td style="text-align: right;">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" class="btn btn-ghost btn-xs" onclick='editPackageModal(@json($pkg))' title="Edit Paket">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('admin.ai.packages.toggle', $pkg->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-xs" title="Toggle Aktif/Nonaktif">
                                            <i class="fa-solid {{ $pkg->is_active ? 'fa-toggle-on text-success' : 'fa-toggle-off text-muted' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.ai.packages.delete', $pkg->id) }}" method="POST" onsubmit="return confirm('Hapus paket token ini?')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs" style="color: var(--danger);" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding: 40px;">
                                Belum ada paket top-up token AI yang dikonfigurasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Section: Recent Top-Up Purchases by Customers --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-cart-shopping" style="color: var(--primary); margin-right: 6px;"></i> Riwayat Pembelian Top-Up Token Customer
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu Order</th>
                        <th>Customer</th>
                        <th>Paket / Tambahan Token</th>
                        <th>Harga Dibayar</th>
                        <th>Status</th>
                        <th>Waktu Kredit Kuota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPurchases as $pur)
                        <tr>
                            <td class="font-mono text-xs text-muted">
                                {{ $pur->created_at?->format('d M Y, H:i') }}
                            </td>
                            <td>
                                <div class="font-bold text-xs" style="color: var(--text);">{{ $pur->customer->name ?? 'Customer' }}</div>
                                <div class="text-xs text-muted">{{ $pur->customer->email ?? '' }}</div>
                            </td>
                            <td>
                                <div class="font-bold text-xs" style="color: var(--text);">{{ $pur->package->name ?? 'Custom AI Top-up' }}</div>
                                <span class="badge badge-primary font-mono text-xs">+{{ number_format($pur->tokens_amount) }} Token</span>
                            </td>
                            <td>
                                <strong style="color: var(--text);">Rp {{ number_format($pur->price_paid, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($pur->status === 'completed')
                                    <span class="badge badge-success" style="font-size: 10px;">LUNAS (COMPLETED)</span>
                                @elseif($pur->status === 'cancelled')
                                    <span class="badge badge-danger" style="font-size: 10px;">DIBATALKAN</span>
                                @else
                                    <span class="badge badge-warning" style="font-size: 10px;">MENUNGGU PEMBAYARAN</span>
                                @endif
                            </td>
                            <td class="font-mono text-xs text-muted">
                                {{ $pur->credited_at ? $pur->credited_at->format('d M Y, H:i') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                Belum ada riwayat pembelian top-up token AI dari customer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($recentPurchases->hasPages())
        <div class="card-footer" style="padding: 14px 20px;">
            {{ $recentPurchases->links() }}
        </div>
    @endif
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

{{-- Modal: Package Config (Create / Edit) --}}
<div id="package-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 520px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md);">
        <div class="card-header flex justify-between items-center">
            <div class="card-title" id="modal-package-title">Tambah Paket Top-Up Token AI</div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closePackageModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.ai.packages.save') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="modal-package-id" value="">
            <div class="card-body" style="display: flex; flex-direction: column; gap: 14px; padding: 20px;">
                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Nama Paket *</label>
                    <input type="text" name="name" id="modal-package-name" required placeholder="Contoh: Pro AI Booster 500K" class="form-input">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">Jumlah Token *</label>
                        <input type="number" name="token_amount" id="modal-package-tokens" required step="10000" min="1000" placeholder="500000" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">Harga (IDR) *</label>
                        <input type="number" name="price" id="modal-package-price" required step="5000" min="0" placeholder="100000" class="form-input">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Deskripsi / Peruntukan</label>
                    <textarea name="description" id="modal-package-desc" rows="2" class="form-textarea" placeholder="Ideal untuk traffic bot WhatsApp & integrasi CRM..."></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">Badge Promo (Opsional)</label>
                        <input type="text" name="badge" id="modal-package-badge" placeholder="Contoh: Paling Populer / Hemat 25%" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">Urutan Tampil</label>
                        <input type="number" name="sort_order" id="modal-package-order" value="1" min="0" class="form-input">
                    </div>
                </div>

                <div class="form-group flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="modal-package-active" value="1" checked style="cursor: pointer;">
                    <label for="modal-package-active" class="text-sm font-semibold" style="cursor: pointer; color: var(--text);">Tampilkan Paket ke Customer</label>
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px;">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closePackageModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk"></i> Simpan Paket</button>
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

function openPackageModal() {
    document.getElementById('modal-package-title').innerText = 'Tambah Paket Top-Up Token AI';
    document.getElementById('modal-package-id').value = '';
    document.getElementById('modal-package-name').value = '';
    document.getElementById('modal-package-tokens').value = '';
    document.getElementById('modal-package-price').value = '';
    document.getElementById('modal-package-desc').value = '';
    document.getElementById('modal-package-badge').value = '';
    document.getElementById('modal-package-order').value = '1';
    document.getElementById('modal-package-active').checked = true;

    document.getElementById('package-modal').style.display = 'flex';
}

function editPackageModal(pkg) {
    document.getElementById('modal-package-title').innerText = 'Edit Paket: ' + pkg.name;
    document.getElementById('modal-package-id').value = pkg.id;
    document.getElementById('modal-package-name').value = pkg.name;
    document.getElementById('modal-package-tokens').value = pkg.token_amount;
    document.getElementById('modal-package-price').value = Math.round(pkg.price);
    document.getElementById('modal-package-desc').value = pkg.description || '';
    document.getElementById('modal-package-badge').value = pkg.badge || '';
    document.getElementById('modal-package-order').value = pkg.sort_order;
    document.getElementById('modal-package-active').checked = Boolean(pkg.is_active);

    document.getElementById('package-modal').style.display = 'flex';
}

function closePackageModal() {
    document.getElementById('package-modal').style.display = 'none';
}
</script>
@endpush
