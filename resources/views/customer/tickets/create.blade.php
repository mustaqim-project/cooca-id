@extends('layouts.customer')
@section('title', 'Create Support Ticket')
@section('breadcrumb')
    <a href="{{ route('customer.tickets.index') }}" class="crumb-link">Tickets</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">New Ticket</span>
@endsection
@section('content')
@php
    $subscriptions = $subscriptions ?? auth('customer')->user()?->subscriptions()->with(['subscriptionPlan', 'license.product'])->get() ?? collect();
@endphp
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-headset" style="color:var(--primary);margin-right:10px;"></i>Create Support Ticket</h1>
        <p class="page-subtitle">Our support team typically responds within 2 business hours.</p>
    </div>
    <a href="{{ route('customer.tickets.index') }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="grid-31">
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Ticket Information</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('customer.tickets.store') }}" enctype="multipart/form-data">
                    @csrf

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <i class="fa-solid fa-circle-xmark"></i>
                            <ul style="margin:0;padding-left:16px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">Subject <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="subject" class="form-input" value="{{ old('subject') }}"
                               placeholder="Briefly describe your issue…" required>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Priority <span style="color:var(--danger);">*</span></label>
                            <select name="priority" class="form-select" required>
                                <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Department</label>
                            <select name="department" class="form-select">
                                <option value="technical">Technical Support</option>
                                <option value="billing">Billing & Payment</option>
                                <option value="account">Account Management</option>
                                <option value="feature">Feature Request</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Related Product</label>
                        <select name="product_id" class="form-select">
                            <option value="">— Select Product —</option>
                            @foreach($subscriptions as $sub)
                                @php $prod = $sub->license?->product ?? $sub->subscriptionPlan?->product ?? null; @endphp
                                @if($prod)
                                    <option value="{{ $prod->id }}" {{ old('product_id') == $prod->id ? 'selected' : '' }}>
                                        {{ $prod->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description <span style="color:var(--danger);">*</span></label>
                        <textarea name="message" class="form-textarea" rows="6" required
                                  placeholder="Please describe your issue in detail. Include error messages, steps to reproduce, screenshots, etc.">{{ old('message') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Attachments</label>
                        <input type="file" name="attachments[]" class="form-input" multiple accept=".jpg,.jpeg,.png,.pdf,.zip,.txt,.log">
                        <div class="form-hint">Max 5 files, 10MB each. Accepted: images, PDF, ZIP, logs.</div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-paper-plane"></i> Submit Ticket
                        </button>
                        <a href="{{ route('customer.tickets.index') }}" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div class="card-body">
                <div class="font-bold mb-3">⚡ Response Times</div>
                <div class="stats-row">
                    <span class="text-sm"><span class="badge badge-danger">High</span> Priority</span>
                    <span class="font-semibold text-sm">Within 1 hour</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm"><span class="badge badge-warning">Medium</span> Priority</span>
                    <span class="font-semibold text-sm">Within 4 hours</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm"><span class="badge badge-muted">Low</span> Priority</span>
                    <span class="font-semibold text-sm">Within 1 day</span>
                </div>
            </div>
        </div>

        <div class="card" style="background:linear-gradient(135deg,var(--primary) 0%,#7C3AED 100%);border:none;color:#fff;">
            <div class="card-body">
                <div class="font-bold mb-2" style="font-size:15px;">💬 Live Chat</div>
                <div style="font-size:13px;opacity:.85;margin-bottom:14px;">Chat directly with our support team via WhatsApp.</div>
                <a href="https://wa.me/{{ setting('company.whatsapp', '') }}" target="_blank"
                   style="background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);color:#fff;padding:9px 18px;border-radius:8px;font-weight:600;font-size:13px;display:inline-flex;align-items:center;gap:8px;">
                    <i class="fa-brands fa-whatsapp"></i> Open WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
