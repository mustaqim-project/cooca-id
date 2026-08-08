@extends('layouts.customer')
@section('title', 'Subscribe — Konfirmasi')
@section('breadcrumb')
    <a href="{{ route('customer.products.index') }}" class="crumb-link">My Services</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Subscribe</span>
@endsection

@section('content')
@php
    $products = \App\Models\Product::with(['subscriptionPlans' => function($q) {
        $q->where('is_active', true)->orderBy('price');
    }])->where('is_active', true)->ordered()->get();

    // Pre-fill from GET params (coming from products/show)
    $preProduct = request('product');
    $prePlan    = request('plan');
    $preDomain  = request('domain');

    $selectedProduct = $preProduct
        ? $products->first(fn($p) => $p->slug === $preProduct || $p->id === $preProduct)
        : null;
    $selectedPlan = null;
    if ($selectedProduct && $prePlan) {
        $selectedPlan = $selectedProduct->subscriptionPlans->firstWhere('id', $prePlan);
    }
    $waNumber = setting('contact.whatsapp', '6282134566667');
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-cart-shopping" style="color:var(--primary);margin-right:10px;"></i>
            Konfirmasi Langganan
        </h1>
        <p class="page-subtitle">Verifikasi konfigurasi dan lanjutkan ke pembayaran.</p>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4">
    <i class="fa-solid fa-circle-xmark"></i>
    <ul style="margin:0;padding-left:16px;">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

<div class="grid-31" style="align-items:start;">

    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Konfigurasi Langganan</div>
            </div>
            <div class="card-body">
                <form id="subscribeForm" method="POST" action="{{ route('customer.subscriptions.store') }}">
                    @csrf

                    {{-- Step 1: Product --}}
                    <div class="form-group">
                        <label class="form-label">Produk <span style="color:var(--danger);">*</span></label>
                        <select name="product_slug" id="productSelect" class="form-select" required onchange="updatePlans()">
                            <option value="">— Pilih Produk —</option>
                            @foreach($products as $prod)
                            <option value="{{ $prod->slug }}"
                                    data-plans="{{ $prod->subscriptionPlans->map(fn($p) => ['id'=>$p->id,'name'=>$p->name,'price'=>$p->price,'billing_cycle'=>$p->billing_cycle,'duration_months'=>$p->duration_months,'discount_percent'=>$p->discount_percent ?? 0])->toJson() }}"
                                    {{ ($selectedProduct && $selectedProduct->id === $prod->id) ? 'selected' : '' }}>
                                {{ $prod->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Step 2: Plan --}}
                    <div class="form-group" id="planGroup">
                        <label class="form-label">Paket <span style="color:var(--danger);">*</span></label>
                        <div id="planCards" style="display:flex;flex-direction:column;gap:8px;">
                            @if($selectedProduct && $selectedProduct->subscriptionPlans->count() > 0)
                                @foreach($selectedProduct->subscriptionPlans as $idx => $plan)
                                <label class="plan-card-select {{ ($selectedPlan ? $selectedPlan->id === $plan->id : $idx === 0) ? 'selected' : '' }}"
                                       for="plan_s_{{ $plan->id }}">
                                    <input type="radio" name="subscription_plan_id" id="plan_s_{{ $plan->id }}"
                                           value="{{ $plan->id }}"
                                           {{ ($selectedPlan ? $selectedPlan->id === $plan->id : $idx === 0) ? 'checked' : '' }}
                                           onchange="selectPlanCard(this)">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-bold">{{ $plan->name }}</div>
                                            <div class="text-xs text-muted">{{ $plan->duration_months ?? 1 }} bulan · {{ ucfirst($plan->billing_cycle) }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold" style="color:var(--primary);">Rp {{ number_format($plan->price, 0, ',', '.') }}</div>
                                            <div class="text-xs text-muted">/{{ $plan->billing_cycle }}</div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            @else
                                <p class="text-xs text-muted">Pilih produk untuk melihat paket yang tersedia.</p>
                                <input type="hidden" name="subscription_plan_id" id="planHidden" value="">
                            @endif
                        </div>
                    </div>

                    {{-- Step 3: Domain --}}
                    @php
                        $waCustomDomainMsg = urlencode("Halo COOCA.ID, saya ingin request setup custom domain untuk langganan baru saya.");
                    @endphp
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Tipe Domain</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="domain_type" value="subdomain" {{ request('domain_type') !== 'custom' ? 'checked' : '' }} onchange="toggleDomainType()">
                                <span>Subdomain COOCA.ID</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="domain_type" value="custom" {{ request('domain_type') === 'custom' ? 'checked' : '' }} onchange="toggleDomainType()">
                                <span>Custom Domain Sendiri</span>
                            </label>
                        </div>
                    </div>

                    <div id="subdomain_container" class="form-group" style="{{ request('domain_type') === 'custom' ? 'display:none;' : '' }}">
                        <label class="form-label">Subdomain Instance <span style="color:var(--danger);">*</span></label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="{{ request('domain_type') === 'custom' ? 'subdomain_ignore' : 'domain' }}" id="subdomain_input" class="form-input"
                                   placeholder="namabisnisanda"
                                   value="{{ request('domain_type') !== 'custom' ? old('domain', $preDomain) : '' }}"
                                   pattern="[a-zA-Z0-9\-]+" title="Huruf kecil, angka, tanda hubung" autocomplete="off" {{ request('domain_type') !== 'custom' ? 'required' : '' }}>
                            <span class="font-bold text-muted text-sm">.cooca.id</span>
                        </div>
                        <div class="form-hint" id="subdomain_hint">Akses ERP Anda di <code>namabisnis.cooca.id</code></div>
                    </div>

                    <div id="custom_domain_container" class="form-group" style="{{ request('domain_type') === 'custom' ? '' : 'display:none;' }}">
                        <label class="form-label">Custom Domain <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="{{ request('domain_type') === 'custom' ? 'domain' : 'custom_domain_ignore' }}" id="custom_domain_input" class="form-input" 
                               placeholder="contoh: erp.bisnisanda.com" autocomplete="off"
                               value="{{ request('domain_type') === 'custom' ? old('domain', $preDomain) : '' }}"
                               {{ request('domain_type') === 'custom' ? 'required' : '' }}>
                        
                        <div style="margin-top: 12px; padding: 12px; border-radius: 8px; background: color-mix(in srgb, var(--primary) 5%, transparent); border: 1px dashed var(--primary);">
                            <div class="text-xs text-muted mb-2">
                                <i class="fa-solid fa-circle-info" style="color:var(--primary);"></i>
                                Gunakan domain Anda sendiri. Setelah pembayaran berhasil, silakan hubungi CS kami. <strong>Catatan:</strong> Jika Anda belum memiliki domain sendiri, akan dikenakan biaya tambahan untuk pembelian domain dan Anda akan dihubungi oleh admin untuk proses lebih lanjut.
                            </div>
                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waCustomDomainMsg }}" target="_blank" class="btn btn-outline btn-sm w-full" style="justify-content:center; border-color:#25D366; color:#25D366;">
                                <i class="fa-brands fa-whatsapp"></i> Hubungi CS via WhatsApp
                            </a>
                        </div>
                    </div>

                    <script>
                        function toggleDomainType() {
                            const type = document.querySelector('input[name="domain_type"]:checked').value;
                            const subInput = document.getElementById('subdomain_input');
                            const cusInput = document.getElementById('custom_domain_input');
                            
                            if (type === 'subdomain') {
                                document.getElementById('subdomain_container').style.display = 'block';
                                document.getElementById('custom_domain_container').style.display = 'none';
                                subInput.required = true;
                                subInput.name = 'domain';
                                cusInput.required = false;
                                cusInput.name = 'custom_domain_ignore';
                            } else {
                                document.getElementById('subdomain_container').style.display = 'none';
                                document.getElementById('custom_domain_container').style.display = 'block';
                                subInput.required = false;
                                subInput.name = 'subdomain_ignore';
                                cusInput.required = true;
                                cusInput.name = 'domain';
                            }
                        }
                    </script>

                    @if($showCompanyFields)
                        <div class="divider mt-4 mb-4" style="border-top: 1px dashed var(--border); margin: 20px 0;"></div>
                        <h3 class="font-bold text-sm mb-3" style="color:var(--primary); display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-building"></i> Lengkapi Profil Perusahaan
                        </h3>
                        <p class="text-xs text-muted mb-4" style="line-height:1.4;">
                            Silakan lengkapi data profil perusahaan Anda terlebih dahulu. Data ini akan otomatis memperbarui profil Anda dan digunakan untuk invoice/laporan.
                        </p>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Nama Perusahaan <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="company_name" class="form-input"
                                    value="{{ old('company_name', $companyProfile?->company_name ?? $customer->business_name) }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">NPWP / Tax ID</label>
                                <input type="text" name="npwp" class="form-input"
                                    value="{{ old('npwp', $companyProfile?->npwp) }}" placeholder="e.g. 01.234.567.8-901.000">
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Bidang Industri <span style="color:var(--danger);">*</span></label>
                                <select name="industry" class="form-select" required>
                                    <option value="">— Pilih Industri —</option>
                                    <option value="retail" {{ old('industry', $companyProfile?->industry) === 'retail' ? 'selected' : '' }}>Retail & E-commerce</option>
                                    <option value="manufacturing" {{ old('industry', $companyProfile?->industry) === 'manufacturing' ? 'selected' : '' }}>Manufacturing & Production</option>
                                    <option value="services" {{ old('industry', $companyProfile?->industry) === 'services' ? 'selected' : '' }}>Professional Services & Consulting</option>
                                    <option value="technology" {{ old('industry', $companyProfile?->industry) === 'technology' ? 'selected' : '' }}>Technology, IT & Software</option>
                                    <option value="construction" {{ old('industry', $companyProfile?->industry) === 'construction' ? 'selected' : '' }}>Construction & Real Estate</option>
                                    <option value="healthcare" {{ old('industry', $companyProfile?->industry) === 'healthcare' ? 'selected' : '' }}>Healthcare & Medical</option>
                                    <option value="hospitality" {{ old('industry', $companyProfile?->industry) === 'hospitality' ? 'selected' : '' }}>Hospitality, Tourism & Food Services</option>
                                    <option value="education" {{ old('industry', $companyProfile?->industry) === 'education' ? 'selected' : '' }}>Education & Training</option>
                                    <option value="agriculture" {{ old('industry', $companyProfile?->industry) === 'agriculture' ? 'selected' : '' }}>Agriculture, Farming & Forestry</option>
                                    <option value="automotive" {{ old('industry', $companyProfile?->industry) === 'automotive' ? 'selected' : '' }}>Automotive, Workshop & Transportation</option>
                                    <option value="finance" {{ old('industry', $companyProfile?->industry) === 'finance' ? 'selected' : '' }}>Finance, Banking & Insurance</option>
                                    <option value="logistics" {{ old('industry', $companyProfile?->industry) === 'logistics' ? 'selected' : '' }}>Logistics & Supply Chain</option>
                                    <option value="wholesale" {{ old('industry', $companyProfile?->industry) === 'wholesale' ? 'selected' : '' }}>Wholesale & Distribution</option>
                                    <option value="creative" {{ old('industry', $companyProfile?->industry) === 'creative' ? 'selected' : '' }}>Entertainment, Media & Creative</option>
                                    <option value="other" {{ old('industry', $companyProfile?->industry) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ukuran Perusahaan <span style="color:var(--danger);">*</span></label>
                                <select name="company_size" class="form-select" required>
                                    <option value="">— Pilih Ukuran —</option>
                                    <option value="1-10" {{ old('company_size', $companyProfile?->company_size) === '1-10' ? 'selected' : '' }}>1-10 Karyawan</option>
                                    <option value="11-50" {{ old('company_size', $companyProfile?->company_size) === '11-50' ? 'selected' : '' }}>11-50 Karyawan</option>
                                    <option value="51-200" {{ old('company_size', $companyProfile?->company_size) === '51-200' ? 'selected' : '' }}>51-200 Karyawan</option>
                                    <option value="201+" {{ old('company_size', $companyProfile?->company_size) === '201+' ? 'selected' : '' }}>200+ Karyawan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Telepon Perusahaan <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="phone" class="form-input"
                                    value="{{ old('phone', $companyProfile?->phone ?? $customer->phone) }}" placeholder="e.g. +628123456789" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Website</label>
                                <input type="url" name="website" class="form-input"
                                    value="{{ old('website', $companyProfile?->website) }}" placeholder="https://example.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Alamat Lengkap <span style="color:var(--danger);">*</span></label>
                            <textarea name="address" class="form-textarea" rows="3" placeholder="Alamat lengkap perusahaan" required>{{ old('address', $companyProfile?->address) }}</textarea>
                        </div>

                        <div class="grid-3 mb-4">
                            <div class="form-group">
                                <label class="form-label">Kota <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="city" class="form-input"
                                    value="{{ old('city', $companyProfile?->city) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Provinsi <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="province" class="form-input"
                                    value="{{ old('province', $companyProfile?->province) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kode Pos <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="postal_code" class="form-input"
                                    value="{{ old('postal_code', $companyProfile?->postal_code) }}" required>
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-lg w-full mt-2" style="justify-content:center;">
                        <i class="fa-solid fa-credit-card"></i> Lanjut ke Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Summary box --}}
        <div class="card" id="summaryBox">
            <div class="card-header">
                <div class="card-title">Ringkasan Order</div>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px;" id="summaryContent">
                @if($selectedProduct && $selectedPlan)
                <div class="stats-row"><span class="text-sm text-muted">Produk</span><span class="font-bold text-sm">{{ $selectedProduct->name }}</span></div>
                <div class="stats-row"><span class="text-sm text-muted">Paket</span><span class="font-bold text-sm">{{ $selectedPlan->name }}</span></div>
                <div class="stats-row"><span class="text-sm text-muted">Durasi</span><span class="font-bold text-sm">{{ $selectedPlan->duration_months ?? 1 }} bulan</span></div>
                <div class="divider"></div>
                <div class="stats-row"><span class="font-bold">Total</span><span class="font-bold text-primary">Rp {{ number_format($selectedPlan->price, 0, ',', '.') }}</span></div>
                @else
                <p class="text-xs text-muted text-center py-2">Pilih produk & paket untuk melihat ringkasan.</p>
                @endif
            </div>
        </div>

        {{-- Info --}}
        <div class="card" style="background:linear-gradient(135deg,var(--primary),#7C3AED);border:none;color:#fff;">
            <div class="card-body">
                <div class="font-bold text-sm mb-2">⚡ Auto-Provisioning</div>
                <p class="text-xs" style="opacity:.9;line-height:1.6;">
                    Setelah pembayaran dikonfirmasi, instance ERP Anda akan aktif otomatis dalam ~60 detik.
                    Database, kredensial admin, dan SSL certificate disiapkan otomatis.
                </p>
            </div>
        </div>

        <div style="text-align:center;padding:12px;border:1px solid var(--border);border-radius:var(--radius);">
            <div class="text-xs text-muted mb-2">Butuh paket custom atau enterprise?</div>
            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo, saya ingin konsultasi paket enterprise COOCA.ID') }}"
               target="_blank" class="btn btn-sm" style="background:#25D366;color:#fff;border-color:#25D366;font-weight:600;width:100%;justify-content:center;">
                <i class="fa-brands fa-whatsapp"></i> Chat WhatsApp
            </a>
        </div>
    </div>
</div>

<style>
.plan-card-select {
    border: 2px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 12px 14px;
    cursor: pointer;
    transition: border-color .15s, background .15s;
    position: relative;
    display: block;
}
.plan-card-select:hover { border-color: var(--primary); background: var(--bg); }
.plan-card-select.selected { border-color: var(--primary); background: color-mix(in srgb, var(--primary) 6%, transparent); }
.plan-card-select input[type=radio] { position: absolute; opacity: 0; pointer-events: none; }
</style>

<script>
function selectPlanCard(radio) {
    document.querySelectorAll('.plan-card-select').forEach(c => c.classList.remove('selected'));
    radio.closest('.plan-card-select').classList.add('selected');
    updateSummary();
}

function updatePlans() {
    const pSelect  = document.getElementById('productSelect');
    const selected = pSelect.options[pSelect.selectedIndex];
    const planCards = document.getElementById('planCards');

    planCards.innerHTML = '<p class="text-xs text-muted">Memuat paket…</p>';

    if (!selected.value) {
        planCards.innerHTML = '<p class="text-xs text-muted">Pilih produk terlebih dahulu.</p>';
        updateSummary();
        return;
    }

    const plans = JSON.parse(selected.getAttribute('data-plans') || '[]');
    if (!plans.length) {
        planCards.innerHTML = '<p class="text-xs text-muted">Belum ada paket untuk produk ini.</p>';
        return;
    }

    planCards.innerHTML = '';
    plans.forEach((p, idx) => {
        const priceStr = `Rp ${new Intl.NumberFormat('id-ID').format(p.price)}`;
        const lbl = document.createElement('label');
        lbl.className = 'plan-card-select' + (idx === 0 ? ' selected' : '');
        lbl.setAttribute('for', 'plan_dyn_' + p.id);
        lbl.innerHTML = `
            <input type="radio" name="subscription_plan_id" id="plan_dyn_${p.id}" value="${p.id}"
                   ${idx === 0 ? 'checked' : ''} onchange="selectPlanCard(this)">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-bold">${p.name}</div>
                    <div class="text-xs text-muted">${p.duration_months || 1} bulan · ${p.billing_cycle}</div>
                </div>
                <div class="text-right">
                    <div class="font-bold" style="color:var(--primary);">${priceStr}</div>
                    <div class="text-xs text-muted">/${p.billing_cycle}</div>
                </div>
            </div>`;
        planCards.appendChild(lbl);
    });
    updateSummary();
}

function updateSummary() {
    const pSelect = document.getElementById('productSelect');
    const checked = document.querySelector('input[name=subscription_plan_id]:checked');
    const box = document.getElementById('summaryContent');
    if (!pSelect.value || !checked) {
        box.innerHTML = '<p class="text-xs text-muted text-center py-2">Pilih produk & paket untuk melihat ringkasan.</p>';
        return;
    }
    const pName = pSelect.options[pSelect.selectedIndex].text;
    const plans = JSON.parse(pSelect.options[pSelect.selectedIndex].getAttribute('data-plans') || '[]');
    const plan  = plans.find(p => p.id === checked.value);
    if (!plan) return;
    const price = new Intl.NumberFormat('id-ID').format(plan.price);
    box.innerHTML = `
        <div class="stats-row"><span class="text-sm text-muted">Produk</span><span class="font-bold text-sm">${pName}</span></div>
        <div class="stats-row"><span class="text-sm text-muted">Paket</span><span class="font-bold text-sm">${plan.name}</span></div>
        <div class="stats-row"><span class="text-sm text-muted">Durasi</span><span class="font-bold text-sm">${plan.duration_months || 1} bulan</span></div>
        <div class="divider"></div>
        <div class="stats-row"><span class="font-bold">Total</span><span class="font-bold text-primary">Rp ${price}</span></div>`;
}

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('subdomain_input');
    const hint = document.getElementById('subdomain_hint');
    let timeout = null;

    if (input) {
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const val = this.value.trim();
            
            if (!val) {
                hint.innerHTML = 'Akses ERP Anda di <code>namabisnis.cooca.id</code>';
                hint.style.color = '';
                return;
            }

            // Basic regex validation first
            if (!/^[a-zA-Z0-9-]+$/.test(val)) {
                hint.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Hanya huruf, angka, dan strip yang diperbolehkan.';
                hint.style.color = 'var(--danger)';
                return;
            }

            hint.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Checking availability...';
            hint.style.color = 'var(--muted)';

            timeout = setTimeout(() => {
                fetch(`{{ route('customer.subscriptions.check-domain', [], false) }}?domain=${val}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(res => {
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        return res.json();
                    })
                    .then(data => {
                        if (data.available) {
                            hint.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${data.message}`;
                            hint.style.color = 'var(--success)';
                        } else {
                            hint.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> ${data.message}`;
                            hint.style.color = 'var(--danger)';
                        }
                    })
                    .catch((err) => {
                        hint.innerHTML = 'Gagal mengecek subdomain (' + err.message + ').';
                        hint.style.color = 'var(--danger)';
                    });
            }, 500); // 500ms debounce
        });
        
        // Trigger check on load if it has value
        if (input.value) {
            input.dispatchEvent(new Event('input'));
        }
    }

    // Init summary on load
    updateSummary();
});
</script>
@endsection
