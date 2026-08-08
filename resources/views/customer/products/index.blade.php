@extends('layouts.customer')
@section('title', 'My Services')
@section('breadcrumb')
    <span class="crumb-current">My Services</span>
@endsection

@section('content')
@php
    $customer    = auth('customer')->user();
    $waNumber    = setting('contact.whatsapp', '6282134566667');

    // Active subscriptions
    $activeSubs  = $customer?->subscriptions()
        ->with(['subscriptionPlan.product', 'license'])
        ->whereIn('status', ['active', 'expired', 'pending'])
        ->latest()
        ->get() ?? collect();

    // Active trials
    $activeTrials = $customer?->erpRequests()
        ->with(['product'])
        ->whereIn('status', ['active_trial', 'submitted', 'waiting_approval', 'waiting_setup', 'in_setup', 'testing', 'domain_setup'])
        ->latest()
        ->get() ?? collect();

    // Total active services
    $totalActive = $activeSubs->where('status', 'active')->count()
                 + $activeTrials->where('status', 'active')->count();
@endphp

{{-- ── PAGE HEADER ── --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-cube" style="color:var(--primary);margin-right:10px;"></i>
            My Services
        </h1>
        <p class="page-subtitle">Kelola langganan aktif Anda dan eksplorasi modul COOCA.ID tersedia.</p>
    </div>
    <div class="page-actions">
        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo, saya ingin konsultasi mengenai produk COOCA.ID') }}"
           target="_blank" class="btn btn-outline">
            <i class="fa-brands fa-whatsapp" style="color:#25D366;"></i> Konsultasi
        </a>
    </div>
</div>

{{-- ── FLASH MESSAGES ── --}}
@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

{{-- ── TABS ── --}}
<div style="display:flex;gap:4px;border-bottom:2px solid var(--border);margin-bottom:24px;overflow-x:auto;" id="serviceTabs">
    <button class="tab-btn active" onclick="switchTab('catalog')" id="tab-catalog">
        <i class="fa-solid fa-store"></i> Katalog Produk
    </button>
    <button class="tab-btn" onclick="switchTab('active')" id="tab-active">
        <i class="fa-solid fa-bolt"></i> Aktif
        @if($totalActive > 0)
            <span class="badge badge-success" style="margin-left:4px;font-size:10px;">{{ $totalActive }}</span>
        @endif
    </button>
    <button class="tab-btn" onclick="switchTab('trials')" id="tab-trials">
        <i class="fa-solid fa-flask"></i> Trial
        @if($activeTrials->count() > 0)
            <span class="badge badge-warning" style="margin-left:4px;font-size:10px;">{{ $activeTrials->count() }}</span>
        @endif
    </button>
</div>

<style>
.tab-btn {
    background: none;
    border: none;
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: color .15s, border-color .15s;
}
.tab-btn:hover { color: var(--text); }
.tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
.tab-pane { display: none; }
.tab-pane.active { display: block; }

/* Plan card selector */
.plan-card-select {
    border: 2px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 16px;
    cursor: pointer;
    transition: border-color .15s, background .15s;
    position: relative;
}
.plan-card-select:hover { border-color: var(--primary); background: var(--bg); }
.plan-card-select.selected { border-color: var(--primary); background: color-mix(in srgb, var(--primary) 6%, transparent); }
.plan-card-select input[type=radio] { position: absolute; opacity: 0; }
</style>

{{-- ══════════════════════════════ TAB: KATALOG ══════════════════════════════ --}}
<div class="tab-pane active" id="pane-catalog">

    <div class="grid-3">
        @forelse($products ?? [] as $product)
        @php
            $isCustom = in_array($product->product_type, ['custom_dev', 'project', 'maintenance']);
            $plans    = $product->subscriptionPlans()->where('is_active', true)->orderBy('price')->get();
            $minPrice = $plans->min('price');

            // Has active subscription for this product
            $hasActive = $activeSubs->where('subscriptionPlan.product.id', $product->id)
                                     ->whereIn('status', ['active'])->count() > 0;
            $hasTrial  = $activeTrials->where('product_id', $product->id)->count() > 0;
        @endphp

        <div class="card card-hover" style="display:flex;flex-direction:column;">
            {{-- Product Header --}}
            <div class="card-body" style="flex:1;display:flex;flex-direction:column;gap:12px;">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        @if($product->thumbnail)
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}"
                                 style="width:52px;height:52px;border-radius:12px;object-fit:cover;border:1px solid var(--border);background:var(--bg);flex-shrink:0;">
                        @elseif($product->icon)
                            <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,var(--primary),#7C3AED);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">{{ $product->icon }}</div>
                        @else
                            <div class="product-logo-placeholder" style="width:52px;height:52px;font-size:20px;border-radius:12px;flex-shrink:0;">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-base" style="line-height:1.3;">{{ $product->name }}</div>
                            <div class="text-xs text-muted">{{ $product->category?->name ?? 'COOCA Module' }}</div>
                        </div>
                    </div>
                    @if($hasActive)
                        <span class="badge badge-success" style="flex-shrink:0;">Active</span>
                    @elseif($hasTrial)
                        <span class="badge badge-warning" style="flex-shrink:0;">Trial</span>
                    @endif
                </div>

                <p class="text-xs text-muted" style="line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;flex:1;">
                    {{ $product->short_description ?? $product->description ?? 'Enterprise ERP module built for scalable operations.' }}
                </p>

                {{-- Price tag --}}
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        @if($isCustom)
                            <span class="text-xs font-bold" style="color:var(--accent);">Harga Custom</span>
                        @elseif($minPrice)
                            <span class="text-xs text-muted">Mulai dari</span>
                            <div class="font-bold text-base" style="color:var(--primary);">Rp {{ number_format($minPrice, 0, ',', '.') }}<span class="text-xs text-muted font-normal">/bln</span></div>
                        @else
                            <span class="text-xs text-muted">Harga tersedia</span>
                        @endif
                    </div>
                    <div class="flex gap-1">
                        <span class="badge badge-primary" style="font-size:9px;">Cloud</span>
                        @if(!$isCustom)<span class="badge badge-purple" style="font-size:9px;">Trial</span>@endif
                    </div>
                </div>

                {{-- Action buttons --}}
                <div style="display:flex;gap:8px;margin-top:4px;">
                    @if($isCustom)
                        {{-- Custom dev → WhatsApp --}}
                        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo, saya ingin info lebih lanjut tentang ' . $product->name) }}"
                           target="_blank"
                           class="btn btn-primary btn-sm w-full" style="justify-content:center;">
                            <i class="fa-brands fa-whatsapp"></i> Hubungi Kami
                        </a>
                    @else
                        {{-- Trial button --}}
                        @if(!$hasTrial && !$hasActive)
                        <a href="{{ route('customer.trials.create', ['product_id' => $product->id]) }}"
                           class="btn btn-outline btn-sm" style="flex:1;justify-content:center;" title="Coba 14 Hari Gratis">
                            <i class="fa-solid fa-flask"></i> Trial
                        </a>
                        @endif

                        {{-- Subscribe / Manage button --}}
                        @if($hasActive)
                        <a href="{{ route('customer.products.show', $product->slug ?? $product->id) }}"
                           class="btn btn-outline btn-sm w-full" style="justify-content:center;">
                            <i class="fa-solid fa-gear"></i> Kelola
                        </a>
                        @else
                        <a href="{{ route('customer.products.show', $product->slug ?? $product->id) }}"
                           class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">
                            <i class="fa-solid fa-rocket"></i> Subscribe
                        </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @empty
        <div class="card" style="grid-column:1/-1;">
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <div class="empty-state-title">Belum Ada Produk Tersedia</div>
                <div class="empty-state-text">Hubungi tim kami untuk solusi enterprise sesuai kebutuhan bisnis Anda.</div>
                <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn btn-primary mt-3">
                    <i class="fa-brands fa-whatsapp"></i> Hubungi Kami
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- ══════════════════════════════ TAB: AKTIF ══════════════════════════════ --}}
<div class="tab-pane" id="pane-active">

    @if($activeSubs->count() === 0)
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">🚀</div>
                <div class="empty-state-title">Belum Ada Langganan Aktif</div>
                <div class="empty-state-text">Pilih produk dari Katalog dan mulai langganan Anda sekarang.</div>
                <button class="btn btn-primary mt-3" onclick="switchTab('catalog')">
                    <i class="fa-solid fa-store"></i> Lihat Katalog
                </button>
            </div>
        </div>
    @else
    <div class="grid-2">
        @foreach($activeSubs as $sub)
        @php
            $plan = $sub->subscriptionPlan;
            $lic  = $sub->license;
            $prod = $lic?->product ?? $plan?->product ?? null;
            $daysLeft = $sub->expires_at ? max(0, (int) now()->diffInDays($sub->expires_at, false)) : null;
            $isExpired = $sub->status === 'expired' || ($daysLeft !== null && $daysLeft <= 0);
            $soonExpire = !$isExpired && $daysLeft !== null && $daysLeft <= 7;
        @endphp
        <div class="card card-hover">
            <div class="card-body">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3">
                        @if($prod?->thumbnail)
                            <img src="{{ $prod->thumbnail_url }}" alt="{{ $prod->name }}" style="width:48px;height:48px;border-radius:10px;object-fit:contain;border:1px solid var(--border);background:var(--bg);padding:4px;flex-shrink:0;">
                        @else
                            <div class="product-logo-placeholder" style="width:48px;height:48px;font-size:18px;border-radius:10px;flex-shrink:0;">
                                {{ strtoupper(substr($prod?->name ?? 'S', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-base">{{ $prod?->name ?? 'Subscription' }}</div>
                            <div class="text-xs text-muted">{{ $plan?->name }} · {{ $plan?->billing_cycle }}</div>
                        </div>
                    </div>
                    <div style="flex-shrink:0;">
                        @if($isExpired) <span class="badge badge-danger">Expired</span>
                        @elseif($soonExpire) <span class="badge badge-warning">Segera Berakhir</span>
                        @elseif($sub->status === 'active') <span class="badge badge-success">Active</span>
                        @else <span class="badge badge-muted">{{ ucfirst($sub->status) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Key info --}}
                <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:14px;">
                    @if($daysLeft !== null)
                    <div class="stats-row" style="margin:0;">
                        <span class="text-xs text-muted">Berakhir</span>
                        <span class="text-xs font-bold {{ $soonExpire || $isExpired ? 'text-danger' : '' }}">
                            {{ $sub->expires_at?->format('d M Y') }}
                            @if($daysLeft > 0) <span class="text-muted">({{ $daysLeft }} hari)</span> @endif
                        </span>
                    </div>
                    @elseif($plan && $plan->duration_months >= 999)
                    <div class="stats-row" style="margin:0;">
                        <span class="text-xs text-muted">Masa Aktif</span>
                        <span class="text-xs font-bold text-success">Selamanya (Lifetime)</span>
                    </div>
                    @endif
                    @if($lic?->domain)
                    <div class="stats-row" style="margin:0;">
                        <span class="text-xs text-muted">Domain</span>
                        <a href="https://{{ $lic->domain }}" target="_blank" class="text-xs text-primary font-semibold">
                            {{ $lic->domain }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i>
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Expiry progress bar --}}
                @if($plan?->duration_months && $plan->duration_months < 999 && $daysLeft !== null && $sub->started_at)
                @php
                    $totalDays = $plan->duration_months * 30;
                    $usedDays = max(0, $totalDays - $daysLeft);
                    $pct = $totalDays > 0 ? min(100, round($usedDays / $totalDays * 100)) : 0;
                    $barColor = $daysLeft <= 7 ? 'var(--danger)' : ($daysLeft <= 30 ? '#f59e0b' : 'var(--primary)');
                @endphp
                <div style="background:var(--border);border-radius:4px;height:5px;margin-bottom:12px;overflow:hidden;">
                    <div style="width:{{ $pct }}%;background:{{ $barColor }};height:100%;transition:width .4s;border-radius:4px;"></div>
                </div>
                @endif

                <div class="flex gap-2">
                    @if($lic?->domain)
                    <a href="https://{{ $lic->domain }}" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-rocket"></i> Launch
                    </a>
                    @endif
                    @if($isExpired || $soonExpire)
                    <a href="{{ route('customer.subscriptions.checkout', $sub->id) }}" class="btn btn-warning btn-sm" style="flex:1;justify-content:center;">
                        <i class="fa-solid fa-rotate"></i> Perpanjang
                    </a>
                    @else
                    <a href="{{ route('customer.subscriptions.show', $sub->id) }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;">
                        Kelola
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ══════════════════════════════ TAB: TRIAL ══════════════════════════════ --}}
<div class="tab-pane" id="pane-trials">

    @if($activeTrials->count() === 0)
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">🧪</div>
                <div class="empty-state-title">Belum Ada Trial Aktif</div>
                <div class="empty-state-text">Coba modul COOCA.ID secara gratis selama 14 hari — tanpa kartu kredit.</div>
                <a href="{{ route('customer.trials.create') }}" class="btn btn-primary mt-3">
                    <i class="fa-solid fa-flask"></i> Request Free Trial
                </a>
            </div>
        </div>
    @else
    <div class="grid-2">
        @foreach($activeTrials as $trial)
        @php
            $trialProd  = $trial->product;
            $trialDays  = $trial->expires_at ? max(0, (int) now()->diffInDays($trial->expires_at, false)) : null;
            $trialExp   = $trial->status === 'expired' || ($trialDays !== null && $trialDays <= 0);
        @endphp
        <div class="card card-hover">
            <div class="card-body">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="product-logo-placeholder" style="width:48px;height:48px;font-size:18px;border-radius:10px;background:linear-gradient(135deg,#8B5CF6,#6366F1);color:#fff;flex-shrink:0;">
                            {{ strtoupper(substr($trialProd?->name ?? 'T', 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-bold text-base">{{ $trialProd?->name ?? 'Trial' }}</div>
                            <div class="text-xs text-muted">14-Day Free Trial</div>
                        </div>
                    </div>
                    @if($trialExp) <span class="badge badge-danger" style="flex-shrink:0;">Expired</span>
                    @elseif($trial->status === 'active') <span class="badge badge-success" style="flex-shrink:0;">Active</span>
                    @elseif($trial->status === 'pending') <span class="badge badge-warning" style="flex-shrink:0;">Pending</span>
                    @else <span class="badge badge-muted" style="flex-shrink:0;">{{ ucfirst($trial->status) }}</span>
                    @endif
                </div>

                @if($trial->domain)
                <div class="stats-row mb-3" style="margin:0 0 12px;">
                    <span class="text-xs text-muted">Domain</span>
                    <a href="https://{{ $trial->domain }}" target="_blank" class="text-xs text-primary font-semibold">
                        {{ $trial->domain }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i>
                    </a>
                </div>
                @endif

                @if($trialDays !== null)
                <div class="stats-row mb-3" style="margin:0 0 12px;">
                    <span class="text-xs text-muted">Sisa Trial</span>
                    <span class="text-xs font-bold {{ $trialDays <= 3 ? 'text-danger' : '' }}">{{ $trialDays }} hari</span>
                </div>
                @endif

                <div class="flex gap-2">
                    @if($trial->domain)
                    <a href="https://{{ $trial->domain }}" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-rocket"></i> Launch
                    </a>
                    @endif
                    @if($trialProd)
                    <a href="{{ route('customer.products.show', $trialProd->slug ?? $trialProd->id) }}"
                       class="btn btn-outline btn-sm" style="flex:1;justify-content:center;">
                        <i class="fa-solid fa-credit-card"></i> Upgrade ke Paid
                    </a>
                    @endif
                    <a href="{{ route('customer.trials.show', $trial->id) }}"
                       class="btn btn-outline btn-sm" style="justify-content:center;">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Request new trial CTA --}}
    <div style="margin-top:20px;text-align:center;">
        <a href="{{ route('customer.trials.create') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-plus"></i> Request Trial Baru
        </a>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('pane-' + tab).classList.add('active');
    // Remember tab
    sessionStorage.setItem('serviceTab', tab);
}
// Restore last tab on load
const savedTab = sessionStorage.getItem('serviceTab');
if (savedTab && document.getElementById('tab-' + savedTab)) switchTab(savedTab);
</script>
@endsection
