@extends('layouts.customer')
@section('title', 'Project Checkout')
@section('breadcrumb')
    <a href="{{ route('customer.projects.index') }}" class="crumb-link">Projects</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('customer.projects.show', $project->id) }}" class="crumb-link">{{ $project->project_name }}</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Checkout</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-credit-card" style="color:var(--primary);margin-right:10px;"></i>
            Project Checkout
        </h1>
        <p class="page-subtitle">Selesaikan pembayaran untuk termin proyek Anda.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.projects.show', $project->id) }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">Rincian Pembayaran Proyek</div>
    </div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:20px;">
        <div>
            <div class="text-xs text-muted font-bold uppercase mb-1">Project</div>
            <div class="font-bold text-lg">{{ $project->project_name }}</div>
        </div>

        <div>
            <div class="text-xs text-muted font-bold uppercase mb-1">Nomor Invoice</div>
            <div class="font-mono text-sm">{{ $invoice->invoice_number }}</div>
        </div>

        <div>
            <div class="text-xs text-muted font-bold uppercase mb-1">Deskripsi Pembayaran</div>
            <div class="text-sm font-semibold">{{ $transaction->description ?? 'Project milestone payment' }}</div>
        </div>

        <div class="divider" style="border-top:1px solid var(--border);"></div>

        <div class="flex justify-between items-center">
            <div class="font-bold text-base">Total Tagihan:</div>
            <div class="text-2xl font-bold" style="color:var(--primary);">
                Rp {{ number_format($transaction->net_amount, 0, ',', '.') }}
            </div>
        </div>

        @if($invoice->status === 'paid')
            <div class="alert alert-success" style="text-align:center;padding:15px;background:#def7ec;color:#03543f;border-radius:var(--radius);font-weight:bold;">
                <i class="fa-solid fa-circle-check"></i> Pembayaran Lunas
            </div>
        @else
            <div style="text-align:center;padding:10px 0;">
                <i class="fa-solid fa-lock" style="color:var(--primary);font-size:32px;margin-bottom:8px;display:block;"></i>
                <div class="font-bold text-sm">Transaksi Pembayaran Siap</div>
                <div class="text-xs text-muted mt-1">Klik tombol di bawah untuk membayar aman via Midtrans.</div>
            </div>

            <button id="pay-button" class="btn btn-primary w-full" style="justify-content:center;font-size:15px;padding:14px;">
                <i class="fa-solid fa-credit-card"></i> Bayar Sekarang via Midtrans
            </button>

            @if(isset($snapUrl) && $snapUrl)
                <a href="{{ $snapUrl }}" class="btn btn-outline w-full" style="justify-content:center;font-size:13px;" target="_blank">
                    <i class="fa-solid fa-external-link-alt"></i> Buka di Halaman Baru
                </a>
            @endif

            @php
                $isSandbox = config('services.midtrans.sandbox', true);
                $midtransJsUrl = $isSandbox ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js';
                $clientKey = config('services.midtrans.client_key');
            @endphp
            <script src="{{ $midtransJsUrl }}" data-client-key="{{ $clientKey }}"></script>
            <script>
                document.getElementById('pay-button').onclick = function () {
                    snap.pay('{{ $snapToken }}', {
                        onSuccess: function(result) {
                            window.location.href = '{{ route('customer.payments.success') }}';
                        },
                        onPending: function(result) {
                            window.location.href = '{{ route('customer.payments.pending') }}';
                        },
                        onError: function(result) {
                            window.location.href = '{{ route('customer.payments.failed') }}';
                        },
                        onClose: function() {
                            // User closed the popup
                        }
                    });
                };
            </script>
        @endif
    </div>
</div>
@endsection
