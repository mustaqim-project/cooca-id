@extends('layouts.admin')

@section('title', 'Voucher Detail — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.vouchers.index') }}">Vouchers</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Voucher: {{ $voucher->code ?? 'PROMO' }}</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <code class="badge badge-purple text-2xl font-bold mb-4">{{ $voucher->code ?? 'PROMO' }}</code>
        <div class="text-sm my-2">
            Diskon: <strong>
                @if(($voucher->type ?? '') == 'percentage')
                    {{ $voucher->value ?? 0 }}%
                @else
                    Rp {{ number_format($voucher->value ?? 0, 0, ',', '.') }}
                @endif
            </strong>
        </div>
        <div class="text-xs text-muted mb-4">Penggunaan: {{ $voucher->used_count ?? 0 }} / {{ $voucher->max_uses ?? 'Unlimited' }}</div>
        
        <h3 class="font-bold mb-2">Riwayat Penggunaan</h3>
        <div class="table-responsive">
            <table class="table" style="font-size:12px;">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Invoice</th>
                        <th>Diskon Didapat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usages as $usage)
                    <tr>
                        <td>{{ $usage->used_at ? \Carbon\Carbon::parse($usage->used_at)->format('d M Y H:i') : '-' }}</td>
                        <td>
                            <div class="font-bold">{{ $usage->customer->name ?? 'Unknown' }}</div>
                            <div class="text-muted" style="font-size:10px;">{{ $usage->customer->email ?? '' }}</div>
                        </td>
                        <td>
                            @if($usage->transaction)
                            <code>{{ $usage->transaction->invoice_number }}</code>
                            @else
                            -
                            @endif
                        </td>
                        <td class="font-bold" style="color:var(--success);">
                            Rp {{ number_format($usage->discount_amount ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat penggunaan untuk voucher ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
