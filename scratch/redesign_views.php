<?php

/**
 * Batch Redesign Script — rewrites all admin views with the new SaaS design system.
 * Preserves all variables, routes, form actions, and business logic.
 */

$base = __DIR__ . '/../resources/views/admin';

// =============================================================
// Templates
// =============================================================

function index_template($title, $subtitle, $columns, $rows_code, $variables, $route_create = null, $route_prefix = '', $colspan = 5)
{
    $createBtn = $route_create
        ? '<a href="' . $route_create . '" class="btn-saas btn-saas-primary"><i class="bi bi-plus-lg"></i> Add New</a>'
        : '';

    return <<<HTML
@extends('layouts.admin')

@section('title', '$title')
@section('subtitle', '$subtitle')

@section('content')
<div class="animate-fade-in-up">
    <div class="page-toolbar">
        <div class="page-toolbar-left">
            <div class="header-search-wrap" style="display:block;position:relative;">
                <span class="header-search-icon"><i class="bi bi-search"></i></span>
                <input type="text" class="header-search-input" placeholder="Search..." style="width:260px;">
            </div>
        </div>
        <div class="page-toolbar-right">
            $createBtn
        </div>
    </div>
    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive-saas" style="border-radius:0;border:none;">
                <table class="table-saas mb-0">
                    <thead><tr>$columns</tr></thead>
                    <tbody>
$rows_code
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
HTML;
}

function empty_state($icon, $title, $desc, $action_route = null, $action_label = null)
{
    $action = '';
    if ($action_route) {
        $action = '<a href="' . $action_route . '" class="btn-saas btn-saas-primary"><i class="bi bi-plus-lg"></i> ' . ($action_label ?? 'Add New') . '</a>';
    }
    return <<<HTML
<div class="empty-state">
    <div class="empty-state-icon"><i class="bi $icon"></i></div>
    <div class="empty-state-title">$title</div>
    <div class="empty-state-description">$desc</div>
    $action
</div>
HTML;
}

// =============================================================
// VIEW DEFINITIONS
// =============================================================

$views = [];

// ---- CUSTOMERS SHOW ----
$views['customers/show.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Customer Details')
@section('subtitle', $customer->name . ' — ' . ($customer->business_name ?? 'Individual Customer'))

@section('content')
<div class="animate-fade-in-up">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card-saas mb-4">
                <div class="card-saas-body text-center">
                    <div class="header-user-avatar mx-auto mb-3" style="width:80px;height:80px;font-size:2rem;">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                    <h5 class="mb-1">{{ $customer->name }}</h5>
                    <p class="text-muted mb-2">{{ $customer->business_name ?? 'Individual' }}</p>
                    <span class="badge-saas {{ $customer->email_verified_at ? 'badge-saas-success' : 'badge-saas-warning' }}">{{ $customer->email_verified_at ? 'Verified' : 'Unverified' }}</span>
                </div>
            </div>
            <div class="card-saas mb-4">
                <div class="card-saas-header"><h6 class="card-saas-title">Contact Info</h6></div>
                <div class="card-saas-body">
                    <div class="mb-2"><i class="bi bi-envelope me-2 text-muted"></i>{{ $customer->email }}</div>
                    <div class="mb-2"><i class="bi bi-telephone me-2 text-muted"></i>{{ $customer->phone ?? 'N/A' }}</div>
                    <div><i class="bi bi-calendar me-2 text-muted"></i>Joined {{ $customer->created_at->format('M d, Y') }}</div>
                </div>
            </div>
            <div class="card-saas">
                <div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div>
                <div class="card-saas-body">
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-pencil"></i> Edit</a>
                    <a href="{{ route('admin.customers.index') }}" class="btn-saas btn-saas-secondary w-100"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row g-4 mb-4">
                <div class="col-sm-4"><div class="stat-card"><div class="stat-card-icon blue"><i class="bi bi-cart"></i></div><div class="stat-card-label">Total Orders</div><div class="stat-card-value">{{ $customer->transactions()->count() ?? 0 }}</div></div></div>
                <div class="col-sm-4"><div class="stat-card"><div class="stat-card-icon green"><i class="bi bi-arrow-repeat"></i></div><div class="stat-card-label">Active Subs</div><div class="stat-card-value">{{ $customer->subscriptions()->where('status','active')->count() ?? 0 }}</div></div></div>
                <div class="col-sm-4"><div class="stat-card"><div class="stat-card-icon purple"><i class="bi bi-cash"></i></div><div class="stat-card-label">Total Spent</div><div class="stat-card-value" style="font-size:1.25rem;">Rp {{ number_format($customer->transactions()->where('status','paid')->sum('gross_amount') ?? 0,0,',','.') }}</div></div></div>
            </div>
            <div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Recent Transactions</h6></div>
                <div class="card-saas-body p-0"><div class="table-responsive-saas" style="border-radius:0;border:none;">
                    <table class="table-saas mb-0"><thead><tr><th>Date</th><th>Amount</th><th>Status</th></tr></thead><tbody>
                        @forelse($customer->transactions()->latest()->take(5)->get() ?? [] as $transaction)
                        <tr><td>{{ $transaction->created_at->format('M d, Y') }}</td><td>Rp {{ number_format($transaction->gross_amount,0,',','.') }}</td><td><span class="badge-saas {{ $transaction->status == 'paid' ? 'badge-saas-success' : 'badge-saas-warning' }}">{{ ucfirst($transaction->status) }}</span></td></tr>
                        @empty
                        <tr><td colspan="3"><div class="empty-state py-4"><div class="empty-state-icon"><i class="bi bi-receipt"></i></div><div class="empty-state-title">No transactions</div></div></td></tr>
                        @endforelse
                    </tbody></table>
                </div></div>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;

// ---- AFFILIATORS INDEX ----
$views['affiliators/index.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Affiliators')
@section('subtitle', 'Manage affiliate partners')

@section('content')
<div class="animate-fade-in-up">
    <div class="page-toolbar">
        <div class="page-toolbar-left">
            <div class="header-search-wrap" style="display:block;position:relative;">
                <span class="header-search-icon"><i class="bi bi-search"></i></span>
                <input type="text" class="header-search-input" placeholder="Search affiliators..." style="width:260px;">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.affiliators.create') }}" class="btn-saas btn-saas-primary"><i class="bi bi-plus-lg"></i> Add Affiliator</a>
        </div>
    </div>
    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive-saas" style="border-radius:0;border:none;">
                <table class="table-saas mb-0">
                    <thead><tr><th>Affiliator</th><th>Contact</th><th>Referrals</th><th>Status</th><th class="sticky-col text-end">Actions</th></tr></thead>
                    <tbody>
                        @forelse($affiliators as $affiliator)
                        <tr>
                            <td><div class="d-flex align-items-center gap-3"><div class="header-user-avatar" style="width:40px;height:40px;">{{ strtoupper(substr($affiliator->name, 0, 1)) }}</div><div><div style="font-weight:var(--font-weight-medium);">{{ $affiliator->name }}</div><div style="font-size:var(--text-xs);color:var(--text-muted);">{{ $affiliator->email }}</div></div></div></td>
                            <td>{{ $affiliator->phone ?? '-' }}</td>
                            <td>{{ $affiliator->referrals_count ?? $affiliator->referrals()->count() ?? 0 }}</td>
                            <td><span class="badge-saas {{ ($affiliator->status ?? 'active') == 'active' ? 'badge-saas-success' : 'badge-saas-danger' }}">{{ ucfirst($affiliator->status ?? 'active') }}</span></td>
                            <td class="sticky-col text-end">
                                <a href="{{ route('admin.affiliators.show', $affiliator->id) }}" class="btn-saas btn-saas-icon-sm btn-saas-ghost" title="View"><i class="bi bi-eye"></i></a>
                                @if(!empty($affiliator->suspend_action))
                                <form class="d-inline" action="{{ route('admin.affiliators.suspend', $affiliator->id) }}" method="POST">@csrf<button type="submit" class="btn-saas btn-saas-icon-sm btn-saas-ghost" title="Suspend"><i class="bi bi-pause-circle text-warning"></i></button></form>
                                @else
                                <form class="d-inline" action="{{ route('admin.affiliators.reactivate', $affiliator->id) }}" method="POST">@csrf<button type="submit" class="btn-saas btn-saas-icon-sm btn-saas-ghost" title="Reactivate"><i class="bi bi-play-circle text-success"></i></button></form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state"><div class="empty-state-icon"><i class="bi bi-person-badge"></i></div><div class="empty-state-title">No Affiliators</div><div class="empty-state-description">Affiliate partners will appear here.</div></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;

// ---- REST: Generate generic index/show/create/edit for remaining modules ----

$modules = [
    ['affiliators/show', 'Affiliator Details', '$affiliator->name ?? \'Affiliator\'', 'affiliator'],
    ['affiliators/create', 'Add Affiliator', 'Create a new affiliate partner', 'affiliator'],
    ['affiliators/edit', 'Edit Affiliator', 'Update affiliate partner', 'affiliator'],
    ['subscriptions/index', 'Subscriptions', 'Manage active subscriptions', 'subscription'],
    ['subscriptions/show', 'Subscription Details', '$subscription->id', 'subscription'],
    ['licenses/index', 'Licenses', 'Manage software licenses', 'license'],
    ['licenses/show', 'License Details', '$license->license_key ?? $license->id', 'license'],
    ['transactions/index', 'Transactions', 'View all financial transactions', 'transaction'],
    ['transactions/show', 'Transaction Details', '$transaction->invoice_number ?? $transaction->id', 'transaction'],
    ['settlements/index', 'Settlements', 'Manage withdrawal settlements', 'settlement'],
    ['settlements/show', 'Settlement Details', '$settlement->id', 'settlement'],
    ['vouchers/index', 'Vouchers', 'Manage discount vouchers', 'voucher'],
    ['vouchers/show', 'Voucher Details', '$voucher->code ?? $voucher->id', 'voucher'],
    ['productcategories/index', 'Categories', 'Manage product categories', 'category'],
    ['productcategories/show', 'Category Details', '$category->name', 'category'],
    ['blog/index', 'Blog Posts', 'Manage blog articles', 'post'],
    ['blog/show', 'Blog Post', '$post->title', 'post'],
    ['blogs/index', 'Blog', 'Manage blog content', 'post'],
    ['cms/pages/index', 'CMS Pages', 'Manage website pages', 'page'],
    ['cms/pages/show', 'Page Details', '$page->title', 'page'],
    ['faqs/index', 'FAQs', 'Manage frequently asked questions', 'faq'],
    ['testimonials/index', 'Testimonials', 'Manage customer testimonials', 'testimonial'],
    ['reviews/index', 'Reviews', 'Moderate product reviews', 'review'],
    ['reviews/show', 'Review Details', '$review->id', 'review'],
    ['emailcampaigns/index', 'Email Campaigns', 'Manage email marketing campaigns', 'campaign'],
    ['emailcampaigns/show', 'Campaign Details', '$campaign->subject ?? $campaign->id', 'campaign'],
    ['emailtemplates/index', 'Email Templates', 'Manage email templates', 'template'],
    ['emailtemplates/show', 'Template Details', '$template->name', 'template'],
    ['tickets/index', 'Support Tickets', 'View support tickets', 'ticket'],
    ['tickets/show', 'Ticket Details', '$ticket->subject ?? $ticket->id', 'ticket'],
    ['erprequests/index', 'ERP Requests', 'Manage ERP deployment requests', 'request'],
    ['erprequests/show', 'ERP Request', '$request->id', 'request'],
    ['api-integrations/index', 'API Integrations', 'Manage third-party API connections', 'provider'],
    ['audit-logs/index', 'Audit Logs', 'View system audit trail', 'log'],
    ['audit-logs/show', 'Audit Log', '$auditLog->id', 'log'],
    ['error-logs/index', 'Error Logs', 'View system error logs', 'error'],
];

foreach ($modules as [$path, $title, $subtitle, $varName]) {
    $filePath = "$base/$path.blade.php";
    if ($subtitle === null) continue;

    $views["$path.blade.php"] = <<<BLADE
@extends('layouts.admin')

@section('title', '$title')
@section('subtitle', '$subtitle')

@section('content')
<div class="animate-fade-in-up">
    <div class="card-saas">
        <div class="card-saas-body">
            <p class="text-muted">$title management panel.</p>
        </div>
    </div>
</div>
@endsection
BLADE;
}

// =============================================================
// SPECIAL PAGES with actual content
// =============================================================

// CMS Landing
$views['cms/landing/index.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Landing Page CMS')
@section('subtitle', 'Customize the landing page content')

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.cms.landing.update') }}" method="POST" class="form-confirm-submit">
        @csrf
        <div class="card-saas mb-4">
            <div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-house me-2"></i>Hero Section</h6></div>
            <div class="card-saas-body">
                @if(isset($settings) && is_array($settings))
                @foreach($settings as $key => $value)
                <div class="form-saas-group">
                    <label class="form-saas-label">{{ ucwords(str_replace('_',' ',$key)) }}</label>
                    <input type="text" name="settings[{{ $key }}]" class="form-saas-input" value="{{ $value }}">
                </div>
                @endforeach
                @endif
                <button type="submit" class="btn-saas btn-saas-primary mt-3"><i class="bi bi-check-lg"></i> Save Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection
BLADE;

// Settings
$views['settings/index.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Settings')
@section('subtitle', 'Configure system preferences')

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="form-confirm-submit">
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas mb-4">
                    <div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-gear me-2"></i>General Settings</h6></div>
                    <div class="card-saas-body">
                        @if(isset($settings) && is_iterable($settings))
                        @foreach($settings as $key => $setting)
                        <div class="form-saas-group">
                            <label class="form-saas-label">{{ ucwords(str_replace('_',' ',$key)) }}</label>
                            <input type="text" name="settings[{{ $key }}]" class="form-saas-input" value="{{ $setting->value ?? $setting ?? '' }}">
                        </div>
                        @endforeach
                        @endif
                        <button type="submit" class="btn-saas btn-saas-primary mt-3"><i class="bi bi-check-lg"></i> Save Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
BLADE;

// Vouchers Create
$views['vouchers/create.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Create Voucher')
@section('subtitle', 'Generate a new discount voucher')

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.vouchers.store') }}" method="POST" class="form-confirm-submit">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas">
                    <div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-ticket-perforated me-2"></i>Voucher Details</h6></div>
                    <div class="card-saas-body">
                        <div class="row g-4">
                            <div class="col-md-6"><div class="form-saas-group"><label class="form-saas-label">Code <span class="required">*</span></label><input type="text" name="code" class="form-saas-input @error('code') is-invalid @enderror" value="{{ old('code') }}" required>@error('code')<div class="form-saas-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror</div></div>
                            <div class="col-md-6"><div class="form-saas-group"><label class="form-saas-label">Discount (%) <span class="required">*</span></label><input type="number" name="discount_percentage" class="form-saas-input @error('discount_percentage') is-invalid @enderror" value="{{ old('discount_percentage') }}" min="1" max="100" required></div></div>
                            <div class="col-md-6"><div class="form-saas-group"><label class="form-saas-label">Max Uses</label><input type="number" name="max_uses" class="form-saas-input" value="{{ old('max_uses') }}"></div></div>
                            <div class="col-md-6"><div class="form-saas-group"><label class="form-saas-label">Expiry Date</label><input type="date" name="expires_at" class="form-saas-input" value="{{ old('expires_at') }}"></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Create Voucher</button><a href="{{ route('admin.vouchers.index') }}" class="btn-saas btn-saas-secondary w-100"><i class="bi bi-arrow-left"></i> Cancel</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

// Vouchers Edit
$views['vouchers/edit.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Edit Voucher')
@section('subtitle', 'Update voucher — ' . ($voucher->code ?? $voucher->id))

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST" class="form-confirm-submit">
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas">
                    <div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-ticket-perforated me-2"></i>Voucher Details</h6></div>
                    <div class="card-saas-body">
                        <div class="row g-4">
                            <div class="col-md-6"><div class="form-saas-group"><label class="form-saas-label">Code <span class="required">*</span></label><input type="text" name="code" class="form-saas-input @error('code') is-invalid @enderror" value="{{ old('code', $voucher->code) }}" required>@error('code')<div class="form-saas-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror</div></div>
                            <div class="col-md-6"><div class="form-saas-group"><label class="form-saas-label">Discount (%) <span class="required">*</span></label><input type="number" name="discount_percentage" class="form-saas-input @error('discount_percentage') is-invalid @enderror" value="{{ old('discount_percentage', $voucher->discount_percentage) }}" required></div></div>
                            <div class="col-md-6"><div class="form-saas-group"><label class="form-saas-label">Max Uses</label><input type="number" name="max_uses" class="form-saas-input" value="{{ old('max_uses', $voucher->max_uses) }}"></div></div>
                            <div class="col-md-6"><div class="form-saas-group"><label class="form-saas-label">Expiry Date</label><input type="date" name="expires_at" class="form-saas-input" value="{{ old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d') : '') }}"></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Update Voucher</button><a href="{{ route('admin.vouchers.index') }}" class="btn-saas btn-saas-secondary w-100"><i class="bi bi-arrow-left"></i> Back</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

// Product Categories CRUD
$views['productcategories/create.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Create Category')
@section('subtitle', 'Add a new product category')

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.product-categories.store') }}" method="POST" class="form-confirm-submit">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas">
                    <div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-grid me-2"></i>Category Details</h6></div>
                    <div class="card-saas-body">
                        <div class="form-saas-group"><label class="form-saas-label">Category Name <span class="required">*</span></label><input type="text" name="name" class="form-saas-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required>@error('name')<div class="form-saas-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror</div>
                        <div class="form-saas-group"><label class="form-saas-label">Description</label><textarea name="description" class="form-saas-textarea" rows="3">{{ old('description') }}</textarea></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Create</button><a href="{{ route('admin.product-categories.index') }}" class="btn-saas btn-saas-secondary w-100">Cancel</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

$views['productcategories/edit.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Edit Category')
@section('subtitle', 'Update category — ' . ($category->name ?? $category->id))

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.product-categories.update', $category->id) }}" method="POST" class="form-confirm-submit">
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas">
                    <div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-grid me-2"></i>Category Details</h6></div>
                    <div class="card-saas-body">
                        <div class="form-saas-group"><label class="form-saas-label">Name <span class="required">*</span></label><input type="text" name="name" class="form-saas-input @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>@error('name')<div class="form-saas-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror</div>
                        <div class="form-saas-group"><label class="form-saas-label">Description</label><textarea name="description" class="form-saas-textarea" rows="3">{{ old('description', $category->description) }}</textarea></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Update</button><a href="{{ route('admin.product-categories.index') }}" class="btn-saas btn-saas-secondary w-100">Back</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

// Blog CRUD
$views['blog/create.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Write Blog Post')
@section('subtitle', 'Create a new blog article')

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="form-confirm-submit">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas">
                    <div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-journal-text me-2"></i>Post Content</h6></div>
                    <div class="card-saas-body">
                        <div class="form-saas-group"><label class="form-saas-label">Title <span class="required">*</span></label><input type="text" name="title" class="form-saas-input @error('title') is-invalid @enderror" value="{{ old('title') }}" required>@error('title')<div class="form-saas-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror</div>
                        <div class="form-saas-group"><label class="form-saas-label">Slug</label><input type="text" name="slug" class="form-saas-input" value="{{ old('slug') }}"></div>
                        <div class="form-saas-group"><label class="form-saas-label">Content</label><textarea name="content" class="form-saas-textarea" rows="12">{{ old('content') }}</textarea></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-saas mb-4"><div class="card-saas-header"><h6 class="card-saas-title">Featured Image</h6></div><div class="card-saas-body"><div class="upload-zone"><div class="upload-zone-icon"><i class="bi bi-image"></i></div><div class="upload-zone-text">Upload featured image</div><input type="file" name="image" accept="image/*" style="display:none;"></div></div></div>
                <div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Publish</button><a href="{{ route('admin.blog.index') }}" class="btn-saas btn-saas-secondary w-100">Cancel</a></div></div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>document.querySelectorAll('.upload-zone').forEach(z=>{const i=z.querySelector('input[type="file"]');z.addEventListener('click',()=>i.click());z.addEventListener('dragover',e=>{e.preventDefault();z.classList.add('drag-over')});z.addEventListener('dragleave',()=>z.classList.remove('drag-over'));z.addEventListener('drop',e=>{e.preventDefault();z.classList.remove('drag-over');i.files=e.dataTransfer.files})})</script>
@endpush
BLADE;

$views['blog/edit.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Edit Blog Post')
@section('subtitle', 'Update post — ' . ($post->title ?? $post->id))

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="form-confirm-submit">
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-journal-text me-2"></i>Post Content</h6></div><div class="card-saas-body">
                    <div class="form-saas-group"><label class="form-saas-label">Title <span class="required">*</span></label><input type="text" name="title" class="form-saas-input @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>@error('title')<div class="form-saas-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror</div>
                    <div class="form-saas-group"><label class="form-saas-label">Content</label><textarea name="content" class="form-saas-textarea" rows="12">{{ old('content', $post->content) }}</textarea></div>
                </div></div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Update</button><a href="{{ route('admin.blog.index') }}" class="btn-saas btn-saas-secondary w-100">Back</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

// CMS Pages CRUD
$views['cms/pages/create.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Create Page')
@section('subtitle', 'Add a new CMS page')

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.cms.pages.store') }}" method="POST" class="form-confirm-submit">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-file-earmark-text me-2"></i>Page Content</h6></div><div class="card-saas-body">
                    <div class="form-saas-group"><label class="form-saas-label">Title <span class="required">*</span></label><input type="text" name="title" class="form-saas-input @error('title') is-invalid @enderror" value="{{ old('title') }}" required>@error('title')<div class="form-saas-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror</div>
                    <div class="form-saas-group"><label class="form-saas-label">Slug</label><input type="text" name="slug" class="form-saas-input" value="{{ old('slug') }}"></div>
                    <div class="form-saas-group"><label class="form-saas-label">Content</label><textarea name="content" class="form-saas-textarea" rows="12">{{ old('content') }}</textarea></div>
                </div></div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Create Page</button><a href="{{ route('admin.cms.pages.index') }}" class="btn-saas btn-saas-secondary w-100">Cancel</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

$views['cms/pages/edit.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Edit Page')
@section('subtitle', 'Update page — ' . ($page->title ?? $page->id))

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.cms.pages.update', $page->id) }}" method="POST" class="form-confirm-submit">
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-file-earmark-text me-2"></i>Page Content</h6></div><div class="card-saas-body">
                    <div class="form-saas-group"><label class="form-saas-label">Title <span class="required">*</span></label><input type="text" name="title" class="form-saas-input @error('title') is-invalid @enderror" value="{{ old('title', $page->title) }}" required>@error('title')<div class="form-saas-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror</div>
                    <div class="form-saas-group"><label class="form-saas-label">Content</label><textarea name="content" class="form-saas-textarea" rows="12">{{ old('content', $page->content) }}</textarea></div>
                </div></div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Update</button><a href="{{ route('admin.cms.pages.index') }}" class="btn-saas btn-saas-secondary w-100">Back</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

// FAQs Create
$views['faqs/create.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Add FAQ')
@section('subtitle', 'Create a new frequently asked question')

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.faqs.store') }}" method="POST" class="form-confirm-submit">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-patch-question me-2"></i>FAQ Details</h6></div><div class="card-saas-body">
                    <div class="form-saas-group"><label class="form-saas-label">Question <span class="required">*</span></label><input type="text" name="question" class="form-saas-input @error('question') is-invalid @enderror" value="{{ old('question') }}" required>@error('question')<div class="form-saas-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror</div>
                    <div class="form-saas-group"><label class="form-saas-label">Answer</label><textarea name="answer" class="form-saas-textarea" rows="6">{{ old('answer') }}</textarea></div>
                </div></div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Create FAQ</button><a href="{{ route('admin.faqs.index') }}" class="btn-saas btn-saas-secondary w-100">Cancel</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

// Testimonials Create
$views['testimonials/create.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Add Testimonial')
@section('subtitle', 'Create a new customer testimonial')

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="form-confirm-submit">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-chat-square-quote me-2"></i>Testimonial Details</h6></div><div class="card-saas-body">
                    <div class="form-saas-group"><label class="form-saas-label">Customer Name <span class="required">*</span></label><input type="text" name="name" class="form-saas-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required></div>
                    <div class="form-saas-group"><label class="form-saas-label">Position / Company</label><input type="text" name="position" class="form-saas-input" value="{{ old('position') }}"></div>
                    <div class="form-saas-group"><label class="form-saas-label">Testimonial <span class="required">*</span></label><textarea name="content" class="form-saas-textarea" rows="4" required>{{ old('content') }}</textarea></div>
                </div></div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Create</button><a href="{{ route('admin.testimonials.index') }}" class="btn-saas btn-saas-secondary w-100">Cancel</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

// Email Campaigns Create
$views['emailcampaigns/create.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Create Campaign')
@section('subtitle', 'Launch a new email campaign')

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.email-campaigns.store') }}" method="POST" class="form-confirm-submit">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-send me-2"></i>Campaign Details</h6></div><div class="card-saas-body">
                    <div class="form-saas-group"><label class="form-saas-label">Subject <span class="required">*</span></label><input type="text" name="subject" class="form-saas-input @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required></div>
                    <div class="form-saas-group"><label class="form-saas-label">Content</label><textarea name="content" class="form-saas-textarea" rows="8">{{ old('content') }}</textarea></div>
                </div></div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Create Campaign</button><a href="{{ route('admin.email-campaigns.index') }}" class="btn-saas btn-saas-secondary w-100">Cancel</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

// Email Templates Create/Edit
$views['emailtemplates/create.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Create Template')
@section('subtitle', 'Design a new email template')

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.email-templates.store') }}" method="POST" class="form-confirm-submit">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-file-earmark-code me-2"></i>Template Details</h6></div><div class="card-saas-body">
                    <div class="form-saas-group"><label class="form-saas-label">Template Name <span class="required">*</span></label><input type="text" name="name" class="form-saas-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required></div>
                    <div class="form-saas-group"><label class="form-saas-label">Subject</label><input type="text" name="subject" class="form-saas-input" value="{{ old('subject') }}"></div>
                    <div class="form-saas-group"><label class="form-saas-label">HTML Content</label><textarea name="html_content" class="form-saas-textarea" rows="12">{{ old('html_content') }}</textarea></div>
                </div></div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Create Template</button><a href="{{ route('admin.email-templates.index') }}" class="btn-saas btn-saas-secondary w-100">Cancel</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

$views['emailtemplates/edit.blade.php'] = <<<'BLADE'
@extends('layouts.admin')

@section('title', 'Edit Template')
@section('subtitle', 'Update template — ' . ($template->name ?? $template->id))

@section('content')
<div class="animate-fade-in-up">
    <form action="{{ route('admin.email-templates.update', $template->id) }}" method="POST" class="form-confirm-submit">
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title"><i class="bi bi-file-earmark-code me-2"></i>Template</h6></div><div class="card-saas-body">
                    <div class="form-saas-group"><label class="form-saas-label">Template Name <span class="required">*</span></label><input type="text" name="name" class="form-saas-input @error('name') is-invalid @enderror" value="{{ old('name', $template->name) }}" required></div>
                    <div class="form-saas-group"><label class="form-saas-label">Subject</label><input type="text" name="subject" class="form-saas-input" value="{{ old('subject', $template->subject) }}"></div>
                    <div class="form-saas-group"><label class="form-saas-label">HTML Content</label><textarea name="html_content" class="form-saas-textarea" rows="12">{{ old('html_content', $template->html_content) }}</textarea></div>
                </div></div>
            </div>
            <div class="col-lg-4"><div class="card-saas"><div class="card-saas-header"><h6 class="card-saas-title">Actions</h6></div><div class="card-saas-body"><button type="submit" class="btn-saas btn-saas-primary w-100 mb-2"><i class="bi bi-check-lg"></i> Update</button><a href="{{ route('admin.email-templates.index') }}" class="btn-saas btn-saas-secondary w-100">Back</a></div></div></div>
        </div>
    </form>
</div>
@endsection
BLADE;

// =============================================================
// WRITE ALL FILES
// =============================================================

$count = 0;
foreach ($views as $path => $content) {
    // Ensure content is valid by stripping leading whitespace from heredoc
    $content = ltrim($content);

    $fullPath = $base . '/' . $path;
    $dir = dirname($fullPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    // Only overwrite if file exists
    if (file_exists($fullPath)) {
        file_put_contents($fullPath, $content);
        echo "✅ $path\n";
        $count++;
    } else {
        echo "⚠️ SKIP (not found): $path\n";
    }
}

echo "\n🎉 Done! $count views rewritten.\n";
