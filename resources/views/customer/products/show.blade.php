@extends('layouts.customer')
@section('title', $product->name . ' — Subscribe')
@section('breadcrumb')
    <a href="{{ route('customer.products.index') }}" class="crumb-link">My Services</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">{{ $product->name }}</span>
@endsection

@section('content')
@php
    $customer   = auth('customer')->user();
    $waNumber   = setting('contact.whatsapp', '6282134566667');
    $isCustom   = in_array($product->product_type, ['custom_dev', 'project', 'maintenance']);
    $waMsg      = urlencode('Halo COOCA.ID, saya ingin info lebih lanjut tentang produk ' . $product->name);

    // Existing active subscription for this product
    $activeSub  = $customer?->subscriptions()
        ->whereHas('subscriptionPlan', fn($q) => $q->where('product_id', $product->id))
        ->where('status', 'active')
        ->with(['subscriptionPlan', 'license'])
        ->first();

    // Existing trial
    $activeTrial = $customer?->erpRequests()
        ->where('product_id', $product->id)
        ->whereIn('status', ['active_trial', 'submitted', 'waiting_approval', 'waiting_setup', 'in_setup', 'testing', 'domain_setup'])
        ->first();

    $features = is_array($product->features) ? $product->features : [];
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title" style="font-size:22px;">
            @if($product->thumbnail)
                <img src="{{ $product->thumbnail_url }}" style="width:36px;height:36px;border-radius:8px;object-fit:cover;vertical-align:middle;margin-right:10px;border:1px solid var(--border);">
            @endif
            {{ $product->name }}
        </h1>
        <p class="page-subtitle">{{ $product->short_description ?? $product->category?->name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.products.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

{{-- ── ACTIVE SUBSCRIPTION NOTICE ── --}}
@if($activeSub)
<div class="alert alert-success mb-4" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <div>
        <i class="fa-solid fa-circle-check"></i>
        <strong>Langganan Aktif</strong> — {{ $activeSub->subscriptionPlan?->name }}
        @if($activeSub->expires_at) · Berakhir {{ $activeSub->expires_at->format('d M Y') }} @endif
    </div>
    <div class="flex gap-2">
        @if($activeSub->license?->domain)
        <a href="https://{{ $activeSub->license->domain }}" target="_blank" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-rocket"></i> Launch
        </a>
        @endif
        <a href="{{ route('customer.subscriptions.checkout', $activeSub->id) }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-rotate"></i> Perpanjang
        </a>
    </div>
</div>
@endif

{{-- ── ACTIVE TRIAL NOTICE ── --}}
@if($activeTrial && !$activeSub)
<div class="alert alert-warning mb-4" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <div>
        <i class="fa-solid fa-flask"></i>
        <strong>Trial Aktif</strong>
        @if($activeTrial->expires_at) · Berakhir {{ \Carbon\Carbon::parse($activeTrial->expires_at)->format('d M Y') }} @endif
    </div>
    @if($activeTrial->domain)
    <a href="https://{{ $activeTrial->domain }}" target="_blank" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-rocket"></i> Buka Trial
    </a>
    @endif
</div>
@endif

<div class="grid-31" style="align-items:start;">

    {{-- ── LEFT: Description + Plans ── --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- About --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tentang {{ $product->name }}</div>
            </div>
            <div class="card-body">
                <p class="text-sm text-muted" style="line-height:1.8;">
                    {{ $product->description ?? 'Modul enterprise COOCA.ID dirancang untuk kebutuhan bisnis modern yang scalable.' }}
                </p>
                @if(count($features) > 0)
                <div class="divider mt-3 mb-3"></div>
                <div class="grid-2">
                    @foreach($features as $feature)
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-check-circle" style="color:var(--success);font-size:13px;flex-shrink:0;"></i>
                        <span>{{ is_array($feature) ? ($feature['title'] ?? $feature['name'] ?? $feature[0] ?? '') : $feature }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Plan Selector / Subscribe Form --}}
        @if($isCustom)
        {{-- CUSTOM PRODUCT → WhatsApp CTA --}}
        <div class="card" style="border:2px solid #25D366;">
            <div class="card-header" style="background:linear-gradient(135deg,#25D366,#128C7E);border-radius:var(--radius-lg) var(--radius-lg) 0 0;">
                <div class="card-title" style="color:#fff;"><i class="fa-brands fa-whatsapp" style="margin-right:6px;"></i> Produk Custom — Hubungi Sales</div>
            </div>
            <div class="card-body">
                <p class="text-sm text-muted mb-4" style="line-height:1.7;">
                    Produk <strong>{{ $product->name }}</strong> adalah solusi custom yang dikerjakan sesuai kebutuhan spesifik bisnis Anda.
                    Tim kami akan memberikan estimasi harga, timeline, dan proposal teknis secara gratis.
                </p>
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsg }}"
                   target="_blank" class="btn btn-primary w-full" style="justify-content:center;font-size:15px;padding:14px;background:#25D366;border-color:#25D366;">
                    <i class="fa-brands fa-whatsapp" style="font-size:18px;"></i>
                    Chat WhatsApp — Konsultasi Gratis
                </a>
                @if($product->demo_url)
                <a href="{{ $product->demo_url }}" target="_blank" class="btn btn-outline w-full mt-2" style="justify-content:center;">
                    <i class="fa-solid fa-desktop"></i> Lihat Demo
                </a>
                @endif
            </div>
        </div>

        @else
        {{-- REGULAR PRODUCT → Plan Picker --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-tags" style="color:var(--primary);margin-right:6px;"></i>
                    Pilih Paket Langganan
                </div>
            </div>
            <div class="card-body">
                @if($plans->count() === 0)
                    <div class="text-center text-muted text-sm py-4">
                        <i class="fa-solid fa-circle-info" style="font-size:24px;margin-bottom:8px;display:block;"></i>
                        Belum ada paket tersedia. Hubungi kami untuk informasi harga.
                    </div>
                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsg }}" target="_blank"
                       class="btn btn-primary w-full" style="justify-content:center;">
                        <i class="fa-brands fa-whatsapp"></i> Tanya Harga via WhatsApp
                    </a>
                @else
                <form method="GET" action="{{ route('customer.subscriptions.create') }}" id="subscribeForm">
                    <input type="hidden" name="product" value="{{ $product->slug ?? $product->id }}">

                    {{-- Plan cards --}}
                    <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
                        @foreach($plans as $idx => $plan)
                        <label class="plan-card-select {{ $idx === 0 ? 'selected' : '' }}" for="plan_{{ $plan->id }}">
                            <input type="radio" name="plan" id="plan_{{ $plan->id }}" value="{{ $plan->id }}"
                                   {{ $idx === 0 ? 'checked' : '' }}
                                   onchange="selectPlan(this)">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-bold text-base">{{ $plan->name }}</div>
                                    <div class="text-xs text-muted">
                                        {{ $plan->duration_months ?? 1 }} bulan ·
                                        {{ ucfirst($plan->billing_cycle) }}
                                        @if($plan->description) · {{ $plan->description }} @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($plan->discount_percent > 0)
                                    <div class="text-xs text-muted line-through">Rp {{ number_format($plan->price, 0, ',', '.') }}</div>
                                    @php $discounted = $plan->price * (1 - $plan->discount_percent / 100); @endphp
                                    <div class="font-bold" style="color:var(--primary);">Rp {{ number_format($discounted, 0, ',', '.') }}</div>
                                    <div class="text-xs" style="color:var(--success);">Hemat {{ $plan->discount_percent }}%</div>
                                    @else
                                    <div class="font-bold" style="color:var(--primary);">Rp {{ number_format($plan->price, 0, ',', '.') }}</div>
                                    @endif
                                    <div class="text-xs text-muted">/{{ $plan->billing_cycle }}</div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Domain input --}}
                    @php
                        $trialSubdomain = $activeTrial ? $activeTrial->requested_subdomain : null;
                        $waCustomDomainMsg = urlencode("Halo COOCA.ID, saya ingin request setup custom domain untuk langganan {$product->name} saya.");
                    @endphp
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Tipe Domain</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="domain_type" value="subdomain" checked onchange="toggleDomainType()">
                                <span>Subdomain COOCA.ID</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="domain_type" value="custom" onchange="toggleDomainType()">
                                <span>Custom Domain Sendiri</span>
                            </label>
                        </div>
                    </div>

                    <div id="subdomain_container" class="form-group">
                        <label class="form-label">Subdomain Instance <span style="color:var(--danger);">*</span></label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="domain" id="subdomain_input" class="form-input" placeholder="namabisnisanda"
                                   pattern="[a-zA-Z0-9\-]+" title="Huruf kecil, angka, dan tanda hubung" autocomplete="off" required
                                   value="{{ $trialSubdomain ?? '' }}">
                            <span class="font-bold text-muted text-sm">.cooca.id</span>
                        </div>
                        @if($trialSubdomain)
                        <div class="form-hint" style="color:var(--success); margin-top: 6px;">
                            <i class="fa-solid fa-check-circle"></i> Terisi otomatis dari subdomain trial Anda sebelumnya.
                        </div>
                        @else
                        <div class="form-hint" id="subdomain_hint">Instance Anda akan dapat diakses di <code>namabisnis.cooca.id</code></div>
                        @endif
                    </div>

                    <div id="custom_domain_container" class="form-group" style="display:none;">
                        <label class="form-label">Custom Domain <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="custom_domain_ignore" id="custom_domain_input" class="form-input" placeholder="contoh: erp.bisnisanda.com" autocomplete="off">
                        
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

                    <button type="submit" class="btn btn-primary w-full" style="justify-content:center;font-size:15px;padding:14px;margin-top:4px;">
                        <i class="fa-solid fa-credit-card"></i>
                        Lanjut ke Pembayaran
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- ── RIGHT SIDEBAR ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Product meta card --}}
        <div class="card">
            <div class="card-body text-center">
                @if($product->thumbnail)
                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}"
                         style="width:80px;height:80px;object-fit:contain;border-radius:16px;margin:0 auto 12px;border:1px solid var(--border);padding:6px;">
                @else
                    <div class="product-logo-placeholder" style="width:80px;height:80px;font-size:32px;border-radius:16px;margin:0 auto 12px;">
                        {{ strtoupper(substr($product->name, 0, 1)) }}
                    </div>
                @endif
                <div class="font-bold text-lg">{{ $product->name }}</div>
                <div class="text-xs text-muted mb-3">v{{ $product->version ?? '5.0' }} · {{ $product->product_type_label }}</div>

                @if($product->demo_url)
                <a href="{{ $product->demo_url }}" target="_blank" class="btn btn-outline btn-sm w-full mb-2" style="justify-content:center;">
                    <i class="fa-solid fa-desktop"></i> Live Demo
                </a>
                @endif
            </div>
        </div>

        {{-- Trial CTA (only if not custom, not already trialing, not subscribed) --}}
        @if(!$isCustom && !$activeTrial && !$activeSub)
        <div class="card" style="background:linear-gradient(135deg,#6366f1,#8B5CF6);border:none;color:#fff;">
            <div class="card-body">
                <div class="font-bold text-base mb-2"><i class="fa-solid fa-flask" style="margin-right:6px;"></i>Coba 14 Hari Gratis</div>
                <p class="text-xs mb-3" style="opacity:.9;line-height:1.6;">
                    Akses penuh semua fitur tanpa kartu kredit. Upgrade ke paid kapan saja.
                </p>
                <a href="{{ route('customer.trials.create', ['product_id' => $product->id]) }}"
                   class="btn btn-sm w-full" style="justify-content:center;background:#fff;color:#6366f1;font-weight:700;">
                    <i class="fa-solid fa-rocket"></i> Request Trial Gratis
                </a>
            </div>
        </div>
        @endif

        {{-- WhatsApp CTA --}}
        <div class="card" style="border:1px solid #25D366;">
            <div class="card-body">
                <div class="font-bold text-sm mb-2"><i class="fa-brands fa-whatsapp" style="color:#25D366;margin-right:6px;"></i>Butuh Bantuan?</div>
                <p class="text-xs text-muted mb-3" style="line-height:1.6;">Tim kami siap membantu konsultasi, demo, atau pertanyaan teknis.</p>
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsg }}" target="_blank"
                   class="btn btn-sm w-full" style="justify-content:center;background:#25D366;color:#fff;border-color:#25D366;font-weight:600;">
                    <i class="fa-brands fa-whatsapp"></i> Chat Sekarang
                </a>
            </div>
        </div>

        {{-- Info points --}}
        <div style="border:1px solid var(--border);border-radius:var(--radius);padding:14px;">
            <div class="text-sm font-bold mb-2"><i class="fa-solid fa-shield-halved" style="color:var(--success);margin-right:6px;"></i>Kenapa COOCA.ID?</div>
            <div style="display:flex;flex-direction:column;gap:7px;">
                @foreach(['Cloud-hosted & auto-provisioned', 'SLA 99.9% uptime', 'Multi-tenant enterprise', 'Support via WhatsApp & ticket', 'Data backup harian'] as $p)
                <div class="flex items-center gap-2 text-xs text-muted">
                    <i class="fa-solid fa-check" style="color:var(--success);flex-shrink:0;"></i> {{ $p }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.plan-card-select {
    border: 2px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 14px 16px;
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
function selectPlan(radio) {
    document.querySelectorAll('.plan-card-select').forEach(c => c.classList.remove('selected'));
    radio.closest('.plan-card-select').classList.add('selected');
    // Update form hidden plan input
    document.querySelector('#subscribeForm input[name=plan]') &&
        (document.querySelector('#subscribeForm input[name=plan]').value = radio.value);
}

// Handle form redirect — pass plan into subscriptions/create
const form = document.getElementById('subscribeForm');
if (form) {
    form.addEventListener('submit', function(e) {
        const checked = form.querySelector('input[name=plan]:checked');
        if (checked) {
            // plan already in name=plan, just let it through as GET params
        }
    });

    const input = document.getElementById('subdomain_input');
    const hint = document.getElementById('subdomain_hint');
    let timeout = null;

    if (input) {
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const val = this.value.trim();
            
            if (!val) {
                hint.innerHTML = 'Instance Anda akan dapat diakses di <code>namabisnis.cooca.id</code>';
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
}
</script>
@endsection
