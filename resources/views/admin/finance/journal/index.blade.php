@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.finance.index') }}">Finance</a>
            <span>/</span>
            <span>Jurnal Umum</span>
        </div>
        <h1 class="page-title">Jurnal Umum</h1>
        <p class="page-subtitle">Daftar transaksi debit dan kredit (Journal Entries).</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.accounting.journal.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Buat Jurnal
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Referensi</th>
                        <th>Deskripsi</th>
                        <th style="text-align: right;">Total Debit</th>
                        <th style="text-align: right;">Total Kredit</th>
                    </tr>
                </thead>
            <tbody>
                @forelse($journals as $j)
                    <tr>
                        <td style="font-weight: 500;">{{ \Carbon\Carbon::parse($j->date)->format('d M Y') }}</td>
                        <td style="font-family: monospace;" class="text-muted">{{ $j->reference ?? '-' }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ $j->description ?? 'Jurnal Penyesuaian' }}</div>
                            <div style="margin-top: 8px; padding-left: 12px; border-left: 2px solid var(--border);">
                                @foreach($j->items as $item)
                                    <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px;">
                                        <span class="text-muted">[{{ $item->accountObj->code ?? '' }}] {{ $item->accountObj->name ?? 'Akun Terhapus' }}</span>
                                        <span style="font-family: monospace; display: flex; gap: 16px;">
                                            <span style="color: {{ $item->debit > 0 ? 'var(--success)' : 'transparent' }};">Rp {{ number_format($item->debit, 0, ',', '.') }}</span>
                                            <span style="color: {{ $item->credit > 0 ? 'var(--danger)' : 'transparent' }};">Rp {{ number_format($item->credit, 0, ',', '.') }}</span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td style="font-family: monospace; font-weight: 600; text-align: right;">
                            Rp {{ number_format($j->items->sum('debit'), 0, ',', '.') }}
                        </td>
                        <td style="font-family: monospace; font-weight: 600; text-align: right;">
                            Rp {{ number_format($j->items->sum('credit'), 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <i class="fa-solid fa-book" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                            <p>Belum ada Jurnal tercatat.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @if($journals->hasPages())
    <div class="card-footer">
        {{ $journals->links() }}
    </div>
    @endif
</div>
@endsection
