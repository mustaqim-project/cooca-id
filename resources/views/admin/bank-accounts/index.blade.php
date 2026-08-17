@extends('layouts.admin')

@section('title', 'CMS Rekening Bank Perusahaan — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.settings.index') }}">Settings</a>
            <span>/</span>
            <span>Rekening Bank</span>
        </div>
        <h1 class="page-title">CMS Rekening Bank Perusahaan</h1>
        <p class="page-subtitle">Kelola daftar rekening bank tujuan transfer pembayaran manual, QRIS, dan petunjuk transfer bagi pelanggan.</p>
    </div>
    <div class="page-actions flex gap-2">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-gear"></i> System Settings
        </a>
        <button type="button" class="btn btn-primary" onclick="openCreateModal()">
            <i class="fa-solid fa-plus"></i> Tambah Rekening Baru
        </button>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <div class="font-bold mb-1"><i class="fa-solid fa-circle-xmark"></i> Terdapat kesalahan pengisian form:</div>
        <ul style="margin:0;padding-left:20px;font-size:13px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card mb-6">
    <div class="card-body" style="padding: 16px;">
        <form method="GET" action="{{ route('admin.bank-accounts.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
            <div style="flex:1;min-width:220px;">
                <input type="text" name="search" class="form-input" placeholder="Cari nama bank, nomor rekening, atas nama..." value="{{ request('search') }}">
            </div>
            <div style="min-width:160px;">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.bank-accounts.index') }}" class="btn btn-ghost btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">Urutan</th>
                        <th>Bank / Metode</th>
                        <th>Nomor Rekening</th>
                        <th>Atas Nama (Pemilik)</th>
                        <th>Cabang</th>
                        <th>QRIS / Barcode</th>
                        <th>Status</th>
                        <th>Utama</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $acc)
                        <tr>
                            <td class="text-center font-bold text-muted">{{ $acc->sort_order }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @if($acc->logo_url)
                                        <img src="{{ $acc->logo_url }}" alt="{{ $acc->bank_name }}" style="height:28px;max-width:60px;object-fit:contain;border-radius:4px;">
                                    @else
                                        <div style="width:36px;height:36px;border-radius:8px;background:{{ $acc->badge_color }};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:bold;font-size:11px;">
                                            {{ substr($acc->bank_name, 0, 3) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-sm" style="color:var(--text);">{{ $acc->bank_name }}</div>
                                        @if($acc->bank_code)
                                            <div class="text-xs text-muted">Kode: <code>{{ $acc->bank_code }}</code></div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-mono font-bold text-base" style="color:var(--primary);letter-spacing:0.5px;">
                                    {{ $acc->account_number }}
                                </div>
                                <button type="button" class="btn btn-ghost btn-xs" style="padding:0;font-size:11px;color:var(--text-muted);" onclick="copyToClip('{{ $acc->account_number }}')">
                                    <i class="fa-solid fa-copy"></i> Salin
                                </button>
                            </td>
                            <td>
                                <div class="font-semibold text-sm">{{ $acc->account_holder }}</div>
                            </td>
                            <td>
                                <span class="text-xs text-muted">{{ $acc->branch ?? '—' }}</span>
                            </td>
                            <td>
                                @if($acc->qr_code_url)
                                    <a href="{{ $acc->qr_code_url }}" target="_blank" title="Lihat QR Code">
                                        <img src="{{ $acc->qr_code_url }}" alt="QR Code" style="height:32px;width:32px;object-fit:cover;border-radius:4px;border:1px solid var(--border);">
                                    </a>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.bank-accounts.toggle-active', $acc->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs {{ $acc->is_active ? 'btn-success' : 'btn-outline' }}" style="font-size:11px;padding:4px 10px;">
                                        {{ $acc->is_active ? '🟢 Aktif' : '⚪ Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if($acc->is_primary)
                                    <span class="badge badge-primary" style="font-size:11px;padding:4px 8px;">
                                        <i class="fa-solid fa-star"></i> Utama
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('admin.bank-accounts.set-primary', $acc->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-xs" title="Setel sebagai rekening utama">
                                            Setel Utama
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div class="td-actions" style="justify-content:flex-end;gap:6px;">
                                    <button type="button" class="btn btn-ghost btn-sm" onclick='editAccount(@json($acc))'>
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.bank-accounts.destroy', $acc->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening {{ $acc->bank_name }} ({{ $acc->account_number }})?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger" title="Hapus Rekening">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted" style="padding: 40px;">
                                Belum ada rekening bank yang ditambahkan. Silakan klik tombol <strong>Tambah Rekening Baru</strong> di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($accounts, 'hasPages') && $accounts->hasPages())
        <div class="card-footer">
            {{ $accounts->links() }}
        </div>
    @endif
</div>

{{-- MODAL CREATE REKENING --}}
<div id="createModal" class="modal-overlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div class="modal-card" style="background:#fff;border-radius:var(--radius);max-width:560px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);margin:20px;">
        <div class="modal-header flex justify-between items-center" style="padding:20px 24px;border-bottom:1px solid var(--border);">
            <div class="font-bold text-lg"><i class="fa-solid fa-building-columns" style="color:var(--primary);margin-right:8px;"></i> Tambah Rekening Bank Baru</div>
            <button type="button" class="btn btn-ghost btn-sm" onclick="closeCreateModal()" style="font-size:18px;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.bank-accounts.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="padding:24px;display:flex;flex-direction:column;gap:16px;">
                <div class="grid-2">
                    <div>
                        <label class="form-label text-xs font-bold">Nama Bank <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" class="form-input" placeholder="Contoh: Bank Central Asia (BCA)" required list="bank-presets">
                        <datalist id="bank-presets">
                            <option value="Bank Central Asia (BCA)">
                            <option value="Bank Mandiri">
                            <option value="Bank Rakyat Indonesia (BRI)">
                            <option value="Bank Negara Indonesia (BNI)">
                            <option value="Bank Syariah Indonesia (BSI)">
                            <option value="Bank CIMB Niaga">
                            <option value="Bank Permata">
                            <option value="Bank Danamon">
                            <option value="QRIS / Semua E-Wallet">
                        </datalist>
                    </div>
                    <div>
                        <label class="form-label text-xs font-bold">Kode Bank (Opsional)</label>
                        <input type="text" name="bank_code" class="form-input" placeholder="Contoh: 014">
                    </div>
                </div>

                <div class="grid-2">
                    <div>
                        <label class="form-label text-xs font-bold">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" class="form-input font-mono" placeholder="Contoh: 8830-8899-8800" required>
                    </div>
                    <div>
                        <label class="form-label text-xs font-bold">Atas Nama (Pemilik) <span class="text-danger">*</span></label>
                        <input type="text" name="account_holder" class="form-input" placeholder="Contoh: PT COOCA TECHNOLOGIES INDONESIA" value="PT COOCA TECHNOLOGIES INDONESIA" required>
                    </div>
                </div>

                <div>
                    <label class="form-label text-xs font-bold">Cabang Bank (Opsional)</label>
                    <input type="text" name="branch" class="form-input" placeholder="Contoh: KCP Sudirman Jakarta">
                </div>

                <div class="grid-2">
                    <div>
                        <label class="form-label text-xs font-bold">Logo Bank (Opsional)</label>
                        <input type="file" name="logo" class="form-input" accept="image/*">
                        <div class="text-xs text-muted mt-1">Format: JPG, PNG, WEBP, SVG (Maks 2MB)</div>
                    </div>
                    <div>
                        <label class="form-label text-xs font-bold">QR Code / QRIS Image (Opsional)</label>
                        <input type="file" name="qr_code_image" class="form-input" accept="image/*">
                        <div class="text-xs text-muted mt-1">Upload barcode QRIS jika ada</div>
                    </div>
                </div>

                <div>
                    <label class="form-label text-xs font-bold">Petunjuk Khusus Rekening Ini (Opsional)</label>
                    <textarea name="instructions" class="form-textarea" rows="2" placeholder="Contoh: Transfer tepat hingga digit terakhir agar otomatis terdeteksi."></textarea>
                </div>

                <div class="grid-3" style="align-items:center;background:var(--bg);padding:12px;border-radius:var(--radius);border:1px solid var(--border);">
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked>
                            <span class="font-bold text-xs">Aktifkan Rekening</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_primary" value="1">
                            <span class="font-bold text-xs">Jadikan Rekening Utama</span>
                        </label>
                    </div>
                    <div>
                        <label class="form-label text-xs font-bold" style="margin-bottom:2px;">Urutan</label>
                        <input type="number" name="sort_order" class="form-input form-input-sm" value="0" min="0" style="padding:4px 8px;">
                    </div>
                </div>
            </div>
            <div class="modal-footer flex justify-end gap-2" style="padding:16px 24px;border-top:1px solid var(--border);">
                <button type="button" class="btn btn-ghost" onclick="closeCreateModal()">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Rekening</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT REKENING --}}
<div id="editModal" class="modal-overlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div class="modal-card" style="background:#fff;border-radius:var(--radius);max-width:560px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);margin:20px;">
        <div class="modal-header flex justify-between items-center" style="padding:20px 24px;border-bottom:1px solid var(--border);">
            <div class="font-bold text-lg"><i class="fa-solid fa-pen-to-square" style="color:var(--primary);margin-right:8px;"></i> Edit Rekening Bank</div>
            <button type="button" class="btn btn-ghost btn-sm" onclick="closeEditModal()" style="font-size:18px;">&times;</button>
        </div>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body" style="padding:24px;display:flex;flex-direction:column;gap:16px;">
                <div class="grid-2">
                    <div>
                        <label class="form-label text-xs font-bold">Nama Bank <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" id="edit_bank_name" class="form-input" required list="bank-presets">
                    </div>
                    <div>
                        <label class="form-label text-xs font-bold">Kode Bank (Opsional)</label>
                        <input type="text" name="bank_code" id="edit_bank_code" class="form-input">
                    </div>
                </div>

                <div class="grid-2">
                    <div>
                        <label class="form-label text-xs font-bold">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" id="edit_account_number" class="form-input font-mono" required>
                    </div>
                    <div>
                        <label class="form-label text-xs font-bold">Atas Nama (Pemilik) <span class="text-danger">*</span></label>
                        <input type="text" name="account_holder" id="edit_account_holder" class="form-input" required>
                    </div>
                </div>

                <div>
                    <label class="form-label text-xs font-bold">Cabang Bank (Opsional)</label>
                    <input type="text" name="branch" id="edit_branch" class="form-input">
                </div>

                <div class="grid-2">
                    <div>
                        <label class="form-label text-xs font-bold">Ganti Logo Bank (Opsional)</label>
                        <input type="file" name="logo" class="form-input" accept="image/*">
                        <div id="edit_logo_preview" class="text-xs text-muted mt-1"></div>
                    </div>
                    <div>
                        <label class="form-label text-xs font-bold">Ganti QR Code / QRIS (Opsional)</label>
                        <input type="file" name="qr_code_image" class="form-input" accept="image/*">
                        <div id="edit_qr_preview" class="text-xs text-muted mt-1"></div>
                    </div>
                </div>

                <div>
                    <label class="form-label text-xs font-bold">Petunjuk Khusus Rekening Ini (Opsional)</label>
                    <textarea name="instructions" id="edit_instructions" class="form-textarea" rows="2"></textarea>
                </div>

                <div class="grid-3" style="align-items:center;background:var(--bg);padding:12px;border-radius:var(--radius);border:1px solid var(--border);">
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1">
                            <span class="font-bold text-xs">Aktifkan Rekening</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_primary" id="edit_is_primary" value="1">
                            <span class="font-bold text-xs">Jadikan Rekening Utama</span>
                        </label>
                    </div>
                    <div>
                        <label class="form-label text-xs font-bold" style="margin-bottom:2px;">Urutan</label>
                        <input type="number" name="sort_order" id="edit_sort_order" class="form-input form-input-sm" min="0" style="padding:4px 8px;">
                    </div>
                </div>
            </div>
            <div class="modal-footer flex justify-end gap-2" style="padding:16px 24px;border-top:1px solid var(--border);">
                <button type="button" class="btn btn-ghost" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Perbarui Rekening</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openCreateModal() {
    const modal = document.getElementById('createModal');
    modal.style.display = 'flex';
}

function closeCreateModal() {
    const modal = document.getElementById('createModal');
    modal.style.display = 'none';
}

function openEditModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'flex';
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'none';
}

function editAccount(acc) {
    document.getElementById('editForm').action = "{{ url('admin/bank-accounts') }}/" + acc.id;
    document.getElementById('edit_bank_name').value = acc.bank_name || '';
    document.getElementById('edit_bank_code').value = acc.bank_code || '';
    document.getElementById('edit_account_number').value = acc.account_number || '';
    document.getElementById('edit_account_holder').value = acc.account_holder || '';
    document.getElementById('edit_branch').value = acc.branch || '';
    document.getElementById('edit_instructions').value = acc.instructions || '';
    document.getElementById('edit_sort_order').value = acc.sort_order || 0;
    document.getElementById('edit_is_active').checked = !!acc.is_active;
    document.getElementById('edit_is_primary').checked = !!acc.is_primary;

    const logoPrev = document.getElementById('edit_logo_preview');
    if (acc.logo) {
        logoPrev.innerHTML = 'Logo saat ini: <code>' + acc.logo + '</code>';
    } else {
        logoPrev.innerHTML = '';
    }

    const qrPrev = document.getElementById('edit_qr_preview');
    if (acc.qr_code_image) {
        qrPrev.innerHTML = 'QR saat ini: <code>' + acc.qr_code_image + '</code>';
    } else {
        qrPrev.innerHTML = '';
    }

    openEditModal();
}

function copyToClip(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Nomor rekening ' + text + ' berhasil disalin!');
    }).catch(() => {
        alert('Nomor rekening: ' + text);
    });
}
</script>
@endpush
