@extends('layouts.admin')

@section('title', 'Partner Settlements — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Settlements</span>
        </div>
        <h1 class="page-title">Affiliate Withdrawals & Settlements</h1>
        <p class="page-subtitle">Process payout requests to bank accounts and e-wallets for affiliate partners.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Affiliate Partner</th>
                        <th>Payout Amount</th>
                        <th>Bank / E-Wallet</th>
                        <th>Account Info</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settlements as $st)
                        <tr>
                            <td><code class="text-primary font-bold">WD-{{ $st->id }}</code></td>
                            <td>
                                <div class="font-bold text-sm">{{ $st->affiliator->name ?? 'Partner' }}</div>
                                <div class="text-xs text-muted">{{ $st->affiliator->email ?? '' }}</div>
                            </td>
                            <td class="font-bold text-success text-base">
                                Rp {{ number_format($st->amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge badge-purple">{{ strtoupper($st->bank_name ?? 'BANK') }}</span>
                            </td>
                            <td>
                                <div class="font-semibold text-sm">{{ $st->account_number ?? '' }}</div>
                                <div class="text-xs text-muted">a.n {{ $st->account_holder ?? '' }}</div>
                            </td>
                            <td>
                                @if(($st->status ?? '') === 'approved' || ($st->status ?? '') === 'paid')
                                    <span class="badge badge-success">PAID</span>
                                @elseif(($st->status ?? '') === 'pending')
                                    <span class="badge badge-warning">PENDING</span>
                                @else
                                    <span class="badge badge-danger">REJECTED</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    @if(($st->status ?? '') === 'pending')
                                        <form action="{{ route('admin.settlements.approve', $st->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.settlements.reject', $st->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-muted">Processed</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding: 40px;">No payout requests pending.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($settlements, 'hasPages') && $settlements->hasPages())
        <div class="card-footer">
            {{ $settlements->links() }}
        </div>
    @endif
</div>
@endsection
