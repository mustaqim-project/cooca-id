@extends('layouts.customer')
@section('title', 'Request Free Trial')
@section('breadcrumb')
    <a href="{{ route('customer.trials.index') }}" class="crumb-link">Free Trials</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">New Trial</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-flask" style="color:var(--accent);margin-right:10px;"></i>Request 14-Day Free Trial</h1>
        <p class="page-subtitle">Test COOCA.ID modules risk-free with full enterprise capabilities.</p>
    </div>
    <a href="{{ route('customer.trials.index') }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="grid-31">
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Trial Configuration</div>
            </div>
            <div class="card-body">
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

                <form method="POST" action="{{ route('customer.trials.store') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Select Product / Module <span style="color:var(--danger);">*</span></label>
                        <select name="product_id" class="form-select" required>
                            <option value="">— Select Product —</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (14-Day Trial Available)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Requested Subdomain <span style="color:var(--danger);">*</span></label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="requested_subdomain" id="subdomain_input" class="form-input"
                                   placeholder="mycompany" value="{{ old('requested_subdomain') }}" required autocomplete="off">
                            <span class="font-bold text-muted text-sm">.cooca.id</span>
                        </div>
                        <div class="form-hint" id="subdomain_hint">Only letters, numbers, and hyphens. Example: <code>mycompany.cooca.id</code></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Notes / Custom Requirements</label>
                        <textarea name="notes" class="form-textarea" rows="4"
                                  placeholder="Tell us about your business or specific features you'd like to evaluate…">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-rocket"></i> Start 14-Day Free Trial
                        </button>
                        <a href="{{ route('customer.trials.index') }}" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card" style="background:linear-gradient(135deg,var(--primary) 0%,#7C3AED 100%);color:#fff;border:none;">
            <div class="card-body">
                <div class="font-bold text-base mb-2">🎁 What's included in Trial?</div>
                <ul class="text-xs" style="line-height:1.8;padding-left:16px;opacity:.9;">
                    <li>Full access to all module features</li>
                    <li>Dedicated cloud environment</li>
                    <li>No credit card required</li>
                    <li>Instant provisioning in ~2 minutes</li>
                    <li>Seamless 1-click upgrade to paid</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('subdomain_input');
        const hint = document.getElementById('subdomain_hint');
        let timeout = null;

        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const val = this.value.trim();
            
            if (!val) {
                hint.innerHTML = 'Only letters, numbers, and hyphens. Example: <code>mycompany.cooca.id</code>';
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
                fetch(`{{ route('customer.trials.check-subdomain', [], false) }}?subdomain=${val}`, {
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
    });
</script>
@endsection
