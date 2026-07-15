@extends('layouts.admin')

@section('title', 'Subscription Details')
@section('subtitle', 'View complete details for subscription #{{ substr($subscription->id, 0, 8) }}')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
        <a href="{{ route('admin.subscriptions.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Subscriptions
        </a>
        @if (in_array($subscription->status, ['active', 'trial']))
            <button type="button" class="btn-saas btn-saas-danger btn-saas-sm" onclick="cancelSubscription()">
                <i class="bi bi-x-circle me-1"></i> Cancel Subscription
            </button>
        @endif
    </div>

    <div class="row g-4">
        {{-- Left: main details + timeline --}}
        <div class="col-lg-8">

            {{-- Details card --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header d-flex align-items-center justify-content-between">
                    <h5 class="card-saas-title mb-0">Subscription #{{ substr($subscription->id, 0, 8) }}</h5>
                    @php
                        $badgeMap = [
                            'active' => 'success',
                            'trial' => 'info',
                            'expired' => 'danger',
                            'cancelled' => 'neutral',
                        ];
                        $badge = $badgeMap[$subscription->status] ?? 'neutral';
                    @endphp
                    <span class="badge-saas badge-saas-{{ $badge }}">{{ strtoupper($subscription->status) }}</span>
                </div>
                <div class="card-saas-body p-0">
                    <table class="table mb-0" style="font-size:.9rem">
                        <tbody>
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-normal" style="width:35%">Full Subscription ID</th>
                                <td class="pe-4 py-3 font-monospace">{{ $subscription->id }}</td>
                            </tr>
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-normal">Customer</th>
                                <td class="pe-4 py-3">
                                    <a href="{{ route('admin.customers.show', $subscription->customer_id ?? 0) }}"
                                        class="fw-semibold text-decoration-none">
                                        {{ $subscription->customer->name ?? 'Unknown Customer' }}
                                    </a>
                                    <div class="small text-muted">{{ $subscription->customer->email ?? '' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-normal">Product</th>
                                <td class="pe-4 py-3">
                                    <a href="{{ route('admin.products.show', $subscription->product_id ?? 0) }}"
                                        class="fw-semibold text-decoration-none">
                                        {{ $subscription->product->name ?? 'Unknown Product' }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-normal">Associated License</th>
                                <td class="pe-4 py-3">
                                    @if ($subscription->license)
                                        <a href="{{ route('admin.licenses.show', $subscription->license->id) }}"
                                            class="text-decoration-none">
                                            {{ $subscription->license->domain ?? 'Unconfigured License' }}
                                        </a>
                                        <span
                                            class="badge-saas badge-saas-{{ $subscription->license->status === 'active' ? 'success' : 'neutral' }} ms-2">
                                            {{ ucfirst($subscription->license->status) }}
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">No license generated yet</span>
                                    @endif
                                </td>
                            </tr>
                            @if ($subscription->status === 'cancelled' && $subscription->cancellation_reason)
                                <tr class="table-danger">
                                    <th class="ps-4 py-3 fw-normal text-danger">Cancellation Reason</th>
                                    <td class="pe-4 py-3 text-danger">{{ $subscription->cancellation_reason }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title mb-0"><i class="bi bi-clock-history me-2"></i>Subscription Timeline</h5>
                </div>
                <div class="card-saas-body">
                    <div class="position-relative ps-4" style="border-left:2px solid var(--border)">
                        @foreach ($timeline as $index => $event)
                            <div class="mb-4 position-relative">
                                <div class="position-absolute"
                                    style="left:-1.6rem;top:0;width:1.1rem;height:1.1rem;background:var(--primary);border-radius:50%;border:2px solid #fff">
                                </div>
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold small">{{ $event['event'] }}</div>
                                        <div class="text-muted small">{{ $event['description'] }}</div>
                                    </div>
                                    <div class="text-muted text-end small text-nowrap">
                                        {{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }}<br>
                                        <span
                                            class="fw-normal">{{ \Carbon\Carbon::parse($event['date'])->format('H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: dates + transactions --}}
        <div class="col-lg-4">

            {{-- Important Dates --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title mb-0"><i class="bi bi-calendar3 me-2"></i>Important Dates</h5>
                </div>
                <div class="card-saas-body p-0">
                    <table class="table mb-0" style="font-size:.875rem">
                        <tbody>
                            <tr>
                                <td class="ps-4 text-muted">Created At</td>
                                <td class="pe-4 text-end fw-medium">
                                    {{ \Carbon\Carbon::parse($subscription->created_at)->format('d M Y') }}</td>
                            </tr>
                            @if ($subscription->activated_at)
                                <tr>
                                    <td class="ps-4 text-muted">Activated At</td>
                                    <td class="pe-4 text-end fw-medium">
                                        {{ \Carbon\Carbon::parse($subscription->activated_at)->format('d M Y') }}</td>
                                </tr>
                            @endif
                            <tr class="table-primary">
                                <td class="ps-4 fw-semibold">Expires At</td>
                                <td class="pe-4 text-end fw-bold">
                                    {{ $subscription->expires_at ? \Carbon\Carbon::parse($subscription->expires_at)->format('d M Y') : 'Lifetime' }}
                                </td>
                            </tr>
                            @if ($subscription->cancelled_at)
                                <tr class="table-danger">
                                    <td class="ps-4 fw-semibold text-danger">Cancelled At</td>
                                    <td class="pe-4 text-end fw-bold text-danger">
                                        {{ \Carbon\Carbon::parse($subscription->cancelled_at)->format('d M Y') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Related Transactions --}}
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title mb-0"><i class="bi bi-receipt me-2"></i>Related Transactions</h5>
                </div>
                <div class="card-saas-body p-0">
                    @if ($subscription->transactions && $subscription->transactions->count() > 0)
                        @foreach ($subscription->transactions as $transaction)
                            <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                                <div>
                                    <div class="fw-semibold small">Rp
                                        {{ number_format($transaction->gross_amount, 0, ',', '.') }}</div>
                                    <div class="text-muted" style="font-size:.75rem">
                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y') }}</div>
                                </div>
                                <div class="text-end">
                                    @php
                                        $tBadge = in_array($transaction->status, ['paid', 'settlement'])
                                            ? 'success'
                                            : 'warning';
                                    @endphp
                                    <span
                                        class="badge-saas badge-saas-{{ $tBadge }} mb-1">{{ ucfirst($transaction->status) }}</span><br>
                                    <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                                        class="small text-decoration-none">View</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-4 text-center text-muted small">No transactions linked to this subscription.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Cancel Form --}}
    <form class="form-confirm-submit" id="cancel-form"
        action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" method="POST">
        @csrf
        <input type="hidden" name="reason" id="cancel-reason">
        <input type="hidden" name="immediate" id="cancel-immediate" value="1">
    </form>

    @include('components.swal-alert')
@endsection

@push('scripts')
    <script>
        function cancelSubscription() {
            Swal.fire({
                title: 'Cancel Subscription',
                icon: 'warning',
                html: `
                <p class="mb-3">Are you sure you want to cancel this subscription?</p>
                <textarea id="swal-reason" class="form-control mb-3" rows="3" placeholder="Reason for cancellation (sent to customer)"></textarea>
                <label class="d-flex align-items-center gap-2 justify-content-center">
                    <input type="checkbox" id="swal-immediate" checked>
                    <span>Revoke associated license immediately</span>
                </label>
            `,
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, cancel it!',
                preConfirm: () => {
                    const reason = document.getElementById('swal-reason').value;
                    if (!reason) {
                        Swal.showValidationMessage('Please enter a cancellation reason');
                        return false;
                    }
                    return {
                        reason,
                        immediate: document.getElementById('swal-immediate').checked ? '1' : '0'
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-reason').value = result.value.reason;
                    document.getElementById('cancel-immediate').value = result.value.immediate;
                    document.getElementById('cancel-form').submit();
                }
            });
        }
    </script>
@endpush
