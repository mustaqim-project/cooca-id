@extends('layouts.admin')

@section('title', 'Settlements')
@section('subtitle', 'Manage affiliate withdrawal requests')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search affiliator, amount...">
            </div>
        </div>
        <div class="page-toolbar-right">
            {{-- no create action for settlements --}}
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="settlementsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Affiliator</th>
                            <th>Amount</th>
                            <th>Bank Info</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settlements as $settlement)
                            <tr>
                                <td class="text-muted" style="font-size:0.8rem">{{ $settlement->id }}</td>
                                <td>
                                    <div class="fw-medium">{{ $settlement->affiliator->name ?? 'Unknown' }}</div>
                                    <div class="text-muted" style="font-size:0.8rem">
                                        {{ $settlement->affiliator->email ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="fw-semibold">Rp
                                        {{ number_format($settlement->amount ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <div style="font-size:0.85rem">{{ $settlement->bank_name ?? '-' }}</div>
                                    <div class="text-muted font-monospace" style="font-size:0.8rem">
                                        {{ $settlement->account_number ?? '' }}</div>
                                </td>
                                <td>
                                    @php
                                        $s = $settlement->status ?? 'pending';
                                        $badge = match ($s) {
                                            'paid' => 'success',
                                            'approved' => 'info',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            default => 'neutral',
                                        };
                                    @endphp
                                    <span class="badge-saas badge-saas-{{ $badge }}">{{ ucfirst($s) }}</span>
                                </td>
                                <td class="text-muted" style="font-size:0.85rem">
                                    {{ $settlement->created_at?->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.settlements.show', $settlement->id) }}"
                                        class="btn-saas btn-saas-ghost btn-saas-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-cash-stack"></i></div>
                                        <div class="empty-state-title">No settlement requests</div>
                                        <div class="empty-state-description">Withdrawal requests from affiliators appear
                                            here.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#settlementsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
