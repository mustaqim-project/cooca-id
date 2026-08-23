@extends('layouts.customer')
@section('title', 'License Management')
@section('breadcrumb')
    <span class="crumb-current">Licenses</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-key" style="color:var(--primary);margin-right:10px;"></i>License Management</h1>
        <p class="page-subtitle">Kelola kode lisensi resmi Anda dan aktifkan pada instans ERP.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.products.index') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Lisensi / Trial
        </a>
    </div>
</div>

{{-- Activation Guide Banner --}}
<div class="card mb-4" style="background:var(--bg-secondary);border:1px solid var(--border);box-shadow:none;">
    <div class="card-body" style="padding:16px 20px;">
        <div class="flex items-center gap-3 mb-2">
            <div style="width:32px;height:32px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div class="font-bold text-sm" style="color:var(--text);">Cara Aktivasi Lisensi pada Instans ERP Anda:</div>
        </div>
        <div class="grid-3 text-xs" style="gap:12px;margin-top:8px;">
            <div style="background:var(--card);padding:12px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                <div class="font-bold text-primary mb-1">Langkah 1: Salin Kredensial</div>
                <div class="text-muted">Klik tombol copy pada <strong>License Code</strong> dan <strong>License Key</strong> di bawah ini.</div>
            </div>
            <div style="background:var(--card);padding:12px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                <div class="font-bold text-primary mb-1">Langkah 2: Buka Menu Aktivasi ERP</div>
                <div class="text-muted">Login ke ERP Anda dan buka halaman <code>/admin/license/activate</code>.</div>
            </div>
            <div style="background:var(--card);padding:12px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                <div class="font-bold text-primary mb-1">Langkah 3: Masukkan Kredensial</div>
                <div class="text-muted">Tempelkan Code, Key, dan Email Anda. Sistem akan mengaktivasi lisensi secara instan.</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>License Code & Key</th>
                        <th>Product</th>
                        <th>Plan</th>
                        <th>Domain</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($licenses as $license)
                    <tr>
                        <td>
                            <div style="display:flex;flex-direction:column;gap:4px;">
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-muted" style="min-width:32px;font-weight:600;">Code:</span>
                                    <code style="font-size:11px;background:var(--bg);padding:2px 6px;border-radius:4px;border:1px solid var(--border);font-family:monospace;font-weight:700;color:var(--primary);">
                                        {{ $license->license_code }}
                                    </code>
                                    <button type="button" onclick="copyToClipboard('{{ $license->license_code }}', 'License Code')" class="btn btn-ghost btn-xs" title="Copy License Code" style="padding:2px 5px;font-size:10px;">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-muted" style="min-width:32px;font-weight:600;">Key:</span>
                                    <code style="font-size:11px;background:var(--bg);padding:2px 6px;border-radius:4px;border:1px solid var(--border);font-family:monospace;font-weight:700;color:var(--text);">
                                        {{ $license->token_code }}
                                    </code>
                                    <button type="button" onclick="copyToClipboard('{{ $license->token_code }}', 'License Key')" class="btn btn-ghost btn-xs" title="Copy License Key" style="padding:2px 5px;font-size:10px;">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            @if($license->is_trial)
                                <span class="badge badge-purple" style="margin-top:4px;">Trial</span>
                            @endif
                        </td>
                        <td>
                            <div class="font-semibold text-sm">{{ $license->product?->name ?? '—' }}</div>
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $license->subscriptionPlan?->name ?? 'Standard' }}</span>
                        </td>
                        <td>
                            @if($license->domain)
                                <a href="https://{{ $license->domain }}" target="_blank" class="text-primary text-sm font-semibold">
                                    {{ $license->domain }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i>
                                </a>
                            @else
                                <span class="text-muted text-xs">Auto-bind saat aktivasi ERP</span>
                            @endif
                        </td>
                        <td>
                            @if($license->status === 'active')    <span class="badge badge-success">Active</span>
                            @elseif($license->status === 'inactive') <span class="badge badge-muted">Inactive</span>
                            @elseif($license->status === 'expired')  <span class="badge badge-danger">Expired</span>
                            @elseif($license->status === 'revoked')  <span class="badge badge-danger">Revoked</span>
                            @endif
                        </td>
                        <td class="text-xs text-muted">
                            @if($license->status === 'inactive')
                                <span class="text-muted">Belum Aktif</span>
                            @elseif($license->expires_at)
                                {{ $license->expires_at->format('d M Y') }}
                                @if($license->expires_at->isPast())
                                    <span class="text-danger font-bold"> (expired)</span>
                                @elseif($license->expires_at->diffInDays() <= 30)
                                    <span class="text-warning font-bold"> ({{ $license->expires_at->diffInDays() }}d left)</span>
                                @endif
                            @else
                                Lifetime
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex gap-1 justify-end">
                                <a href="{{ route('customer.licenses.credentials', $license->id) }}" class="btn btn-primary btn-sm" title="Lihat Kredensial Lengkap">
                                    <i class="fa-solid fa-key"></i> Kredensial
                                </a>
                                <a href="{{ route('customer.licenses.show', $license->id) }}" class="btn btn-ghost btn-sm" title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">🔑</div>
                                <div class="empty-state-title">Belum Ada Lisensi</div>
                                <div class="empty-state-text">Lisensi Anda akan otomatis muncul di sini setelah berlangganan atau disetujui untuk trial.</div>
                                <a href="{{ route('customer.products.index') }}" class="btn btn-primary">Lihat Katalog Produk</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($licenses, 'hasPages') && $licenses->hasPages())
        <div class="card-footer">{{ $licenses->links() }}</div>
    @endif
</div>

<script>
function copyToClipboard(text, label = 'Teks') {
    navigator.clipboard.writeText(text).then(() => {
        alert(label + ' berhasil disalin ke clipboard!');
    });
}
</script>
@endsection
