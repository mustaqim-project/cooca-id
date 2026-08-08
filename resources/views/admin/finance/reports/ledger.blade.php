@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.finance.index') }}">Finance</a>
            <span>/</span>
            <span>Buku Besar</span>
        </div>
        <h1 class="page-title">Buku Besar (Ledger)</h1>
        <p class="page-subtitle">Laporan transaksi per akun.</p>
    </div>
</div>

    <!-- Filter -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <form action="{{ route('admin.accounting.reports.ledger') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
                <div style="flex: 1;">
                    <label class="form-label">Pilih Akun (Chart of Account)</label>
                    <select name="account_id" class="form-select">
                        <option value="">Semua Akun (Pilih satu untuk detail)...</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $accountId == $acc->id ? 'selected' : '' }}>[{{ $acc->code }}] {{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>
        </div>
    </div>

    @if($accountId)
    <!-- Ledger Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Referensi</th>
                            <th>Keterangan</th>
                            <th style="text-align: right;">Debit</th>
                            <th style="text-align: right;">Kredit</th>
                            <th style="text-align: right;">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $balance = 0; @endphp
                    @forelse($ledger as $item)
                        @php 
                            // Simplistic balance calc (assume debit increases asset/expense, credit increases liability/equity/revenue)
                            // For a real accounting system, logic depends on account type.
                            $balance += ($item->debit - $item->credit);
                        @endphp
                        <tr>
                            <td style="font-weight: 500;">{{ \Carbon\Carbon::parse($item->journalEntry->date)->format('d M Y') }}</td>
                            <td style="font-family: monospace;" class="text-muted">{{ $item->journalEntry->reference ?? '-' }}</td>
                            <td>{{ $item->description ?? $item->journalEntry->description }}</td>
                            <td style="font-family: monospace; text-align: right; color: {{ $item->debit > 0 ? 'var(--success)' : 'inherit' }}">{{ $item->debit > 0 ? 'Rp '.number_format($item->debit, 0, ',', '.') : '-' }}</td>
                            <td style="font-family: monospace; text-align: right; color: {{ $item->credit > 0 ? 'var(--danger)' : 'inherit' }}">{{ $item->credit > 0 ? 'Rp '.number_format($item->credit, 0, ',', '.') : '-' }}</td>
                            <td style="font-family: monospace; text-align: right; font-weight: bold;">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">Belum ada transaksi di akun ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    @endif
@endsection
