@extends('layouts.admin')

@section('title', 'Subscription & Pricing Plans — ' . ($product->name ?? 'Product') . ' — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.products.index') }}">Products</a>
            <span>/</span>
            <a href="{{ route('admin.products.show', $product->id) }}">{{ $product->name }}</a>
            <span>/</span>
            <span>Pricing Plans</span>
        </div>
        <h1 class="page-title">Pricing Plans: {{ $product->name ?? 'Software' }}</h1>
        <p class="page-subtitle">Atur tier harga langganan, diskon promosi, durasi paket, dan status aktif untuk produk ini.</p>
    </div>
    <div class="page-actions" style="display: flex; gap: 10px;">
        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline">
            <i class="fa-solid fa-eye mr-1"></i> View Detail
        </a>
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline">
            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Product
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Products
        </a>
    </div>
</div>

@if (session('success'))
    <div style="background: var(--success-soft); color: var(--success); padding: 14px 18px; border-radius: var(--radius-sm); border: 1px solid var(--success); margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
        <div style="font-weight: 600;">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: inherit; cursor: pointer; font-size: 16px;">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div style="background: var(--danger-soft); color: var(--danger); padding: 14px 18px; border-radius: var(--radius-sm); border: 1px solid var(--danger); margin-bottom: 24px;">
        <div style="font-weight: 700; margin-bottom: 6px;">
            <i class="fa-solid fa-triangle-exclamation mr-1"></i> Periksa error validasi berikut:
        </div>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid-31" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    {{-- Left: Existing Plans Table --}}
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="card-title" style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-layer-group text-primary"></i>
                <span>Daftar Paket Harga ({{ $plans->count() }} Tiers)</span>
            </div>
            <div class="text-xs text-muted">
                Base Product Price: <strong>Rp {{ number_format($product->base_price ?? 0, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Paket</th>
                            <th>Durasi</th>
                            <th>Harga (IDR)</th>
                            <th>Diskon %</th>
                            <th>Status</th>
                            <th style="text-align: right; min-width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans ?? [] as $plan)
                            @php 
                                $pObj = is_array($plan) ? (object)$plan : $plan; 
                                $origPrice = (float)($pObj->price ?? 0);
                                $discount = (float)($pObj->discount_percent ?? 0);
                                $finalPrice = $discount > 0 ? $origPrice * (1 - $discount / 100) : $origPrice;
                                $savings = $origPrice - $finalPrice;
                            @endphp
                            <tr id="plan-row-{{ $pObj->id }}">
                                <td>
                                    <div class="font-bold text-primary" style="font-size: 14px;">{{ $pObj->name ?? 'Tier' }}</div>
                                    <div class="text-xs text-muted" style="margin-top: 2px;">Order: #{{ $pObj->sort_order ?? 0 }}</div>
                                </td>
                                <td>
                                    @if(($pObj->duration_months ?? 1) >= 999)
                                        <span class="badge badge-success" style="font-weight: 700;">Lifetime Access</span>
                                    @elseif(($pObj->duration_months ?? 1) == 1)
                                        <span class="badge badge-accent">1 Bulan (Monthly)</span>
                                    @elseif(($pObj->duration_months ?? 1) == 12)
                                        <span class="badge badge-purple">12 Bulan (Annually)</span>
                                    @else
                                        <span class="badge badge-muted">{{ $pObj->duration_months ?? 1 }} Bulan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($discount > 0)
                                        <div class="text-xs text-muted" style="text-decoration: line-through;">
                                            Rp {{ number_format($origPrice, 0, ',', '.') }}
                                        </div>
                                        <div class="font-bold text-primary" style="font-size: 15px;">
                                            Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-success font-medium">
                                            Hemat Rp {{ number_format($savings, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="font-bold text-primary" style="font-size: 15px;">
                                            Rp {{ number_format($origPrice, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($discount > 0)
                                        <span class="badge badge-accent" style="font-weight: 700;">
                                            {{ number_format($discount, ($discount == floor($discount) ? 0 : 2)) }}% OFF
                                        </span>
                                    @else
                                        <span class="badge badge-muted">0%</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.products.plans.toggle', [$product->id, $pObj->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="badge {{ ($pObj->is_active ?? true) ? 'badge-success' : 'badge-muted' }}" style="border: none; cursor: pointer;" title="Klik untuk ubah status">
                                            @if($pObj->is_active ?? true)
                                                <span class="status-dot active"></span> Active
                                            @else
                                                <span class="status-dot inactive"></span> Inactive
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="td-actions" style="display: flex; gap: 6px; justify-content: flex-end;">
                                        {{-- Edit Button (Directly opens edit modal without recreate) --}}
                                        <button type="button" 
                                                class="btn btn-outline btn-sm" 
                                                title="Edit Harga & Paket"
                                                onclick="openEditPlanModal({{ json_encode($pObj) }})">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('admin.products.plans.destroy', [$product->id, $pObj->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket \'{{ $pObj->name }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-sm text-danger" title="Hapus Paket">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding: 48px 20px;">
                                    <div style="font-size: 28px; margin-bottom: 8px;"><i class="fa-solid fa-box-open"></i></div>
                                    <div style="font-weight: 600; color: var(--text);">Belum Ada Paket Harga</div>
                                    <p style="font-size: 13px; margin-top: 4px;">Tambahkan tier harga pertama menggunakan form di sebelah kanan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right: Add Pricing Tier Form --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title" style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-plus-circle text-primary"></i>
                <span>Tambah Tier Paket Baru</span>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.plans.store', $product->id) }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Nama Paket *</label>
                    <input type="text" name="name" class="form-input" placeholder="contoh: Bulanan / 1 Tahun Hemat / Lifetime" required value="{{ old('name') }}" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); color: var(--text);">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Durasi Periode *</label>
                    <select name="duration_months" class="form-select" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); color: var(--text);">
                        <option value="1" {{ old('duration_months') == 1 ? 'selected' : '' }}>1 Bulan (Monthly)</option>
                        <option value="3" {{ old('duration_months') == 3 ? 'selected' : '' }}>3 Bulan (Quarterly)</option>
                        <option value="6" {{ old('duration_months') == 6 ? 'selected' : '' }}>6 Bulan (Semi-Annually)</option>
                        <option value="12" {{ old('duration_months', 12) == 12 ? 'selected' : '' }}>12 Bulan (Annually)</option>
                        <option value="24" {{ old('duration_months') == 24 ? 'selected' : '' }}>24 Bulan (2 Tahun)</option>
                        <option value="999" {{ old('duration_months') == 999 ? 'selected' : '' }}>Lifetime (One-Time Purchase)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Harga Normal (IDR) *</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 13px; font-weight: 700; color: var(--text-muted);">Rp</span>
                        <input type="number" step="any" name="price" id="addPriceInput" class="form-input" placeholder="contoh: 700000" required value="{{ old('price', $product->base_price ?? 350000) }}" style="width: 100%; padding: 9px 12px 9px 36px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); color: var(--text);" oninput="updateAddPreview()">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Diskon Promosi (%)</label>
                    <input type="number" step="any" min="0" max="100" name="discount_percent" id="addDiscountInput" class="form-input" placeholder="0 - 100" value="{{ old('discount_percent', 0) }}" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); color: var(--text);" oninput="updateAddPreview()">
                </div>

                {{-- Live Price Preview Box --}}
                <div style="background: var(--bg); border: 1px dashed var(--border); border-radius: var(--radius-sm); padding: 12px; margin-bottom: 16px;">
                    <div class="text-xs text-muted font-semibold">Harga Akhir yang Dibayar User:</div>
                    <div id="addFinalPreview" class="font-bold text-primary" style="font-size: 16px; margin-top: 2px;">
                        Rp 0
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Urutan Tampilan (Sort Order)</label>
                    <input type="number" name="sort_order" class="form-input" value="{{ old('sort_order', 0) }}" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); color: var(--text);">
                </div>

                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                    <input type="checkbox" name="is_active" id="addIsActive" value="1" checked style="width: 16px; height: 16px; accent-color: var(--primary);">
                    <label for="addIsActive" style="font-size: 13.5px; font-weight: 500; cursor: pointer;">Aktifkan Paket Ini</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 11px;">
                    <i class="fa-solid fa-plus mr-1"></i> Simpan Paket Baru
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     EDIT PLAN MODAL (Direct Price & Tier Editing)
═══════════════════════════════════════════════════ --}}
<div id="editPlanModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); max-width: 520px; width: 100%; box-shadow: var(--shadow-xl); overflow: hidden; animation: modalFadeIn 0.2s ease;">
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: var(--bg);">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-pen-to-square text-primary"></i>
                <span>Edit Paket &amp; Harga</span>
            </h3>
            <button type="button" onclick="closeEditPlanModal()" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer;">&times;</button>
        </div>

        <form id="editPlanForm" method="POST" style="padding: 24px;">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Nama Tier Paket *</label>
                <input type="text" name="name" id="editPlanName" class="form-input" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); color: var(--text);">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Durasi Periode *</label>
                <select name="duration_months" id="editPlanDuration" class="form-select" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); color: var(--text);">
                    <option value="1">1 Bulan (Monthly)</option>
                    <option value="3">3 Bulan (Quarterly)</option>
                    <option value="6">6 Bulan (Semi-Annually)</option>
                    <option value="12">12 Bulan (Annually)</option>
                    <option value="24">24 Bulan (2 Tahun)</option>
                    <option value="999">Lifetime (One-Time Purchase)</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 14px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Harga Normal (IDR) *</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 13px; font-weight: 700; color: var(--text-muted);">Rp</span>
                        <input type="number" step="any" name="price" id="editPlanPrice" class="form-input" required style="width: 100%; padding: 9px 12px 9px 36px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); color: var(--text);" oninput="updateEditPreview()">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Diskon (%)</label>
                    <input type="number" step="any" min="0" max="100" name="discount_percent" id="editPlanDiscount" class="form-input" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); color: var(--text);" oninput="updateEditPreview()">
                </div>
            </div>

            {{-- Live Discount Preview --}}
            <div style="background: var(--bg); border: 1px dashed var(--border); border-radius: var(--radius-sm); padding: 12px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="text-xs text-muted">Harga Final Setelah Diskon:</div>
                    <div id="editFinalPreview" class="font-bold text-primary" style="font-size: 16px; margin-top: 2px;">Rp 0</div>
                </div>
                <div id="editSavingsBadge" class="badge badge-accent" style="display: none;">
                    Hemat 0%
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px; align-items: center;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Urutan (Sort Order)</label>
                    <input type="number" name="sort_order" id="editPlanSortOrder" class="form-input" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); color: var(--text);">
                </div>

                <div style="display: flex; align-items: center; gap: 8px; margin-top: 18px;">
                    <input type="checkbox" name="is_active" id="editPlanIsActive" value="1" style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <label for="editPlanIsActive" style="font-size: 13.5px; font-weight: 600; cursor: pointer;">Status Aktif</label>
                </div>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 16px; border-top: 1px solid var(--border);">
                <button type="button" class="btn btn-ghost" onclick="closeEditPlanModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan Harga
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
</style>

<script>
const updateRouteTemplate = "{{ route('admin.products.plans.update', [$product->id, ':plan_id']) }}";

function formatRupiah(number) {
    return 'Rp ' + Number(number || 0).toLocaleString('id-ID');
}

function updateAddPreview() {
    const price = parseFloat(document.getElementById('addPriceInput').value) || 0;
    const discount = parseFloat(document.getElementById('addDiscountInput').value) || 0;
    const finalPrice = discount > 0 ? price * (1 - discount / 100) : price;
    document.getElementById('addFinalPreview').innerText = formatRupiah(finalPrice);
}

function updateEditPreview() {
    const price = parseFloat(document.getElementById('editPlanPrice').value) || 0;
    const discount = parseFloat(document.getElementById('editPlanDiscount').value) || 0;
    const finalPrice = discount > 0 ? price * (1 - discount / 100) : price;
    
    document.getElementById('editFinalPreview').innerText = formatRupiah(finalPrice);
    const savingsBadge = document.getElementById('editSavingsBadge');
    if (discount > 0) {
        savingsBadge.style.display = 'inline-block';
        savingsBadge.innerText = 'Hemat ' + discount + '%';
    } else {
        savingsBadge.style.display = 'none';
    }
}

function openEditPlanModal(plan) {
    const modal = document.getElementById('editPlanModal');
    const form = document.getElementById('editPlanForm');

    form.action = updateRouteTemplate.replace(':plan_id', plan.id);
    document.getElementById('editPlanName').value = plan.name || '';
    document.getElementById('editPlanDuration').value = plan.duration_months || 1;
    document.getElementById('editPlanPrice').value = plan.price || 0;
    document.getElementById('editPlanDiscount').value = plan.discount_percent || 0;
    document.getElementById('editPlanSortOrder').value = plan.sort_order || 0;
    document.getElementById('editPlanIsActive').checked = Boolean(plan.is_active);

    updateEditPreview();
    modal.style.display = 'flex';
}

function closeEditPlanModal() {
    document.getElementById('editPlanModal').style.display = 'none';
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('editPlanModal');
    if (e.target === modal) {
        closeEditPlanModal();
    }
});

// Initialize add preview on page load
document.addEventListener('DOMContentLoaded', function() {
    updateAddPreview();
});
</script>
@endsection
