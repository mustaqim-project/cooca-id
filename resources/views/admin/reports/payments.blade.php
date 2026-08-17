@extends('layouts.admin')

@section('title', 'Laporan Jenis Pembayaran — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Finance & Reporting</span>
            <span>/</span>
            <span>Laporan Jenis Pembayaran</span>
        </div>
        <h1 class="page-title">Laporan & Analisis Jenis Pembayaran</h1>
        <p class="page-subtitle">Komparasi performa pendapatan antara Payment Gateway Midtrans dan Transfer Bank Manual beserta riwayat mutasi lengkap.</p>
    </div>
    <div class="page-actions flex gap-2">
        <a href="{{ route('admin.reports.payments.export', request()->query()) }}" class="btn btn-success">
            <i class="fa-solid fa-file-csv"></i> Export Laporan CSV
        </a>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-credit-card"></i> Transaction Ledger
        </a>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="card mb-6">
    <div class="card-body" style="padding: 16px;">
        <form method="GET" action="{{ route('admin.reports.payments.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label class="form-label text-xs font-bold mb-1">Periode Waktu</label>
                <select name="preset" class="form-select form-select-sm" onchange="toggleDateCustom(this.value); this.form.submit();" style="min-width:140px;">
                    <option value="today" {{ $preset === 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="7days" {{ $preset === '7days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="30days" {{ $preset === '30days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    <option value="this_month" {{ $preset === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="last_month" {{ $preset === 'last_month' ? 'selected' : '' }}>Bulan Lalu</option>
                    <option value="this_year" {{ $preset === 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="all" {{ $preset === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                    <option value="custom" {{ $preset === 'custom' ? 'selected' : '' }}>Kustom Tanggal...</option>
                </select>
            </div>

            <div id="custom-date-start" style="{{ $preset === 'custom' ? 'display:block;' : 'display:none;' }}">
                <label class="form-label text-xs font-bold mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-input form-input-sm" value="{{ $startDate }}">
            </div>

            <div id="custom-date-end" style="{{ $preset === 'custom' ? 'display:block;' : 'display:none;' }}">
                <label class="form-label text-xs font-bold mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-input form-input-sm" value="{{ $endDate }}">
            </div>

            <div>
                <label class="form-label text-xs font-bold mb-1">Metode Pembayaran</label>
                <select name="gateway" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:160px;">
                    <option value="">Semua Metode</option>
                    <option value="midtrans" {{ request('gateway') === 'midtrans' ? 'selected' : '' }}>Midtrans Gateway</option>
                    <option value="manual" {{ request('gateway') === 'manual' ? 'selected' : '' }}>Transfer Bank Manual</option>
                </select>
            </div>

            <div>
                <label class="form-label text-xs font-bold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:130px;">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed / Ditolak</option>
                </select>
            </div>

            <div style="flex:1;min-width:180px;">
                <label class="form-label text-xs font-bold mb-1">Cari Kata Kunci</label>
                <input type="text" name="search" class="form-input form-input-sm" placeholder="No. Invoice, Pelanggan, Pengirim..." value="{{ request('search') }}">
            </div>

            <div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Terapkan</button>
                @if(request()->hasAny(['preset', 'gateway', 'status', 'search', 'start_date', 'end_date']))
                    <a href="{{ route('admin.reports.payments.index') }}" class="btn btn-ghost btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- KPI CARDS --}}
<div class="grid-4 mb-6" style="gap:16px;">
    {{-- Card 1: Total Net Revenue --}}
    <div class="card" style="border-left: 4px solid var(--primary);box-shadow:0 4px 12px rgba(0,0,0,0.03);">
        <div class="card-body" style="padding:18px;">
            <div class="flex justify-between items-center mb-1">
                <span class="text-xs font-bold uppercase text-muted">Total Pendapatan Bersih</span>
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(67,97,238,0.1);color:var(--primary);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div class="font-bold text-2xl" style="color:var(--primary);">
                Rp {{ number_format($stats['total_net_paid'], 0, ',', '.') }}
            </div>
            <div class="text-xs text-muted mt-2">
                Dari <strong>{{ $stats['total_paid_count'] }}</strong> transaksi lunas (Gross: Rp {{ number_format($stats['total_gross_paid'], 0, ',', '.') }})
            </div>
        </div>
    </div>

    {{-- Card 2: Midtrans Revenue & Share --}}
    <div class="card" style="border-left: 4px solid #7209b7;box-shadow:0 4px 12px rgba(0,0,0,0.03);">
        <div class="card-body" style="padding:18px;">
            <div class="flex justify-between items-center mb-1">
                <span class="text-xs font-bold uppercase text-muted">Midtrans Payment Gateway</span>
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(114,9,183,0.1);color:#7209b7;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-bolt"></i>
                </div>
            </div>
            <div class="font-bold text-2xl" style="color:#7209b7;">
                Rp {{ number_format($stats['midtrans_net_revenue'], 0, ',', '.') }}
            </div>
            <div class="text-xs text-muted mt-2 flex justify-between items-center">
                <span><strong>{{ $stats['midtrans_paid_count'] }}</strong> Transaksi</span>
                <span class="badge badge-purple" style="font-size:10px;">{{ $stats['midtrans_share_percent'] }}% Share</span>
            </div>
        </div>
    </div>

    {{-- Card 3: Manual Bank Transfer Revenue & Share --}}
    <div class="card" style="border-left: 4px solid #f59e0b;box-shadow:0 4px 12px rgba(0,0,0,0.03);">
        <div class="card-body" style="padding:18px;">
            <div class="flex justify-between items-center mb-1">
                <span class="text-xs font-bold uppercase text-muted">Transfer Bank Manual</span>
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(245,158,11,0.1);color:#d97706;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
            </div>
            <div class="font-bold text-2xl" style="color:#d97706;">
                Rp {{ number_format($stats['manual_net_revenue'], 0, ',', '.') }}
            </div>
            <div class="text-xs text-muted mt-2 flex justify-between items-center">
                <span><strong>{{ $stats['manual_paid_count'] }}</strong> Transaksi</span>
                <span class="badge badge-warning" style="font-size:10px;">{{ $stats['manual_share_percent'] }}% Share</span>
            </div>
        </div>
    </div>

    {{-- Card 4: Status Breakdown --}}
    <div class="card" style="border-left: 4px solid #10b981;box-shadow:0 4px 12px rgba(0,0,0,0.03);">
        <div class="card-body" style="padding:18px;">
            <div class="flex justify-between items-center mb-1">
                <span class="text-xs font-bold uppercase text-muted">Konversi & Status</span>
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(16,185,129,0.1);color:#10b981;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
            </div>
            <div class="font-bold text-2xl" style="color:#10b981;">
                {{ $stats['total_all_count'] > 0 ? round(($stats['total_paid_count'] / $stats['total_all_count']) * 100, 1) : 0 }}%
            </div>
            <div class="text-xs text-muted mt-2 flex gap-2">
                <span class="text-success"><i class="fa-solid fa-circle"></i> {{ $stats['total_paid_count'] }} Paid</span>
                <span class="text-warning"><i class="fa-solid fa-circle"></i> {{ $stats['total_pending_count'] }} Pending</span>
                <span class="text-danger"><i class="fa-solid fa-circle"></i> {{ $stats['total_failed_count'] }} Failed</span>
            </div>
        </div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="grid-3 mb-6" style="gap:20px;">
    {{-- Left: Time Series Trend Chart (2 cols) --}}
    <div class="card" style="grid-column: span 2;">
        <div class="card-header flex justify-between items-center">
            <div class="card-title"><i class="fa-solid fa-chart-line" style="color:var(--primary);margin-right:8px;"></i> Tren Pendapatan per Metode Pembayaran</div>
            <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $preset)) }}</span>
        </div>
        <div class="card-body">
            @if(count($chartData['labels']) > 0)
                <div style="position:relative;height:280px;">
                    <canvas id="paymentTrendChart"></canvas>
                </div>
            @else
                <div class="text-center text-muted" style="padding:60px 20px;">
                    <i class="fa-solid fa-chart-area" style="font-size:36px;opacity:0.3;margin-bottom:10px;display:block;"></i>
                    Belum ada data transaksi lunas pada rentang waktu yang dipilih.
                </div>
            @endif
        </div>
    </div>

    {{-- Right: Doughnut Proportion Chart (1 col) --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-chart-pie" style="color:var(--primary);margin-right:8px;"></i> Pangsa Jenis Pembayaran</div>
        </div>
        <div class="card-body">
            @if($stats['total_net_paid'] > 0)
                <div style="position:relative;height:220px;">
                    <canvas id="paymentShareChart"></canvas>
                </div>
                <div class="mt-4" style="display:flex;flex-direction:column;gap:8px;font-size:12px;">
                    <div class="flex justify-between items-center">
                        <span><span style="display:inline-block;width:10px;height:10px;background:#7209b7;border-radius:2px;margin-right:6px;"></span> Midtrans Gateway:</span>
                        <strong>Rp {{ number_format($stats['midtrans_net_revenue'], 0, ',', '.') }} ({{ $stats['midtrans_share_percent'] }}%)</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span><span style="display:inline-block;width:10px;height:10px;background:#f59e0b;border-radius:2px;margin-right:6px;"></span> Transfer Bank Manual:</span>
                        <strong>Rp {{ number_format($stats['manual_net_revenue'], 0, ',', '.') }} ({{ $stats['manual_share_percent'] }}%)</strong>
                    </div>
                </div>
            @else
                <div class="text-center text-muted" style="padding:60px 20px;">
                    <i class="fa-solid fa-pie-chart" style="font-size:36px;opacity:0.3;margin-bottom:10px;display:block;"></i>
                    Tidak ada data proporsi.
                </div>
            @endif
        </div>
    </div>
</div>

{{-- COMPARISON MATRIX & MIDTRANS CHANNELS --}}
<div class="grid-2 mb-6" style="gap:20px;">
    {{-- Comparison Card --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Perbandingan Saluran Pembayaran</div>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <th class="text-center">Total Transaksi</th>
                        <th class="text-right">Pendapatan Bersih</th>
                        <th class="text-right">Porsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span class="badge badge-purple"><i class="fa-solid fa-bolt"></i> Midtrans</span>
                                <span class="font-bold text-xs">Payment Gateway Instant</span>
                            </div>
                        </td>
                        <td class="text-center font-bold">{{ $stats['midtrans_paid_count'] }}</td>
                        <td class="text-right font-bold text-primary">Rp {{ number_format($stats['midtrans_net_revenue'], 0, ',', '.') }}</td>
                        <td class="text-right"><span class="badge badge-purple">{{ $stats['midtrans_share_percent'] }}%</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span class="badge badge-warning" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;"><i class="fa-solid fa-building-columns"></i> Transfer</span>
                                <span class="font-bold text-xs">Transfer Bank Manual (Wajib Bukti)</span>
                            </div>
                        </td>
                        <td class="text-center font-bold">{{ $stats['manual_paid_count'] }}</td>
                        <td class="text-right font-bold text-primary">Rp {{ number_format($stats['manual_net_revenue'], 0, ',', '.') }}</td>
                        <td class="text-right"><span class="badge badge-warning">{{ $stats['manual_share_percent'] }}%</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Midtrans Breakdown Sub-channels --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Rincian Saluran Midtrans (E-Wallet, VA, QRIS)</div>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Saluran Midtrans</th>
                        <th class="text-center">Transaksi</th>
                        <th class="text-right">Volume</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($midtransChannels as $mChan)
                        <tr>
                            <td>
                                <span class="font-mono text-xs font-bold uppercase">{{ str_replace('_', ' ', $mChan->payment_type ?? 'Unknown') }}</span>
                            </td>
                            <td class="text-center">{{ $mChan->total_count }}</td>
                            <td class="text-right font-bold text-sm">Rp {{ number_format((float) $mChan->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted" style="padding:20px;">
                                Belum ada transaksi Midtrans pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- DETAILED TRANSACTIONS TABLE --}}
<div class="card">
    <div class="card-header flex justify-between items-center">
        <div>
            <div class="card-title">Daftar Transaksi Pembayaran</div>
            <div class="text-xs text-muted mt-1">Menampilkan seluruh mutasi pembayaran sesuai filter yang diterapkan.</div>
        </div>
        <div>
            <span class="badge badge-primary">{{ $transactions->total() }} Transaksi</span>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Metode Pembayaran</th>
                        <th>Bukti / Detail Saluran</th>
                        <th>Status</th>
                        <th>Gross</th>
                        <th>Diskon</th>
                        <th>Net Revenue</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td>
                                <code class="font-bold text-primary">{{ $tx->invoice_number ?? ('INV-'.$tx->id) }}</code>
                                @if($tx->type)
                                    <div class="text-xs text-muted">{{ ucfirst($tx->type) }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="text-xs font-semibold">{{ $tx->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-muted">{{ $tx->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="font-semibold text-sm">{{ $tx->customer->name ?? 'Client' }}</div>
                                <div class="text-xs text-muted">{{ $tx->customer->email ?? '' }}</div>
                            </td>
                            <td>
                                @if($tx->isManualTransfer())
                                    <span class="badge badge-warning" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;">
                                        <i class="fa-solid fa-building-columns"></i> Transfer Manual
                                    </span>
                                @else
                                    <span class="badge badge-purple">
                                        <i class="fa-solid fa-bolt"></i> Midtrans
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($tx->isManualTransfer())
                                    @if($tx->payment_proof)
                                        <a href="{{ $tx->payment_proof_url }}" target="_blank" class="btn btn-ghost btn-xs" style="color:var(--primary);">
                                            <i class="fa-solid fa-image"></i> Bukti Bayar
                                        </a>
                                        @if($tx->sender_name)
                                            <div class="text-xs text-muted">a/n {{ $tx->sender_name }}</div>
                                        @endif
                                    @else
                                        <span class="text-xs text-danger">Belum Upload</span>
                                    @endif
                                @else
                                    <span class="font-mono text-xs text-muted">{{ $tx->midtransTransaction?->payment_type ?? 'Midtrans' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($tx->status === 'paid')
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> PAID</span>
                                @elseif($tx->status === 'pending')
                                    @if($tx->isManualTransfer() && $tx->payment_proof)
                                        <span class="badge badge-warning" style="background:#ffedd5;color:#9a3412;">
                                            <i class="fa-solid fa-clock"></i> Butuh Verifikasi
                                        </span>
                                    @else
                                        <span class="badge badge-warning">PENDING</span>
                                    @endif
                                @elseif($tx->status === 'failed')
                                    <span class="badge badge-danger">FAILED</span>
                                @else
                                    <span class="badge badge-muted">{{ strtoupper($tx->status) }}</span>
                                @endif
                            </td>
                            <td class="text-xs">Rp {{ number_format($tx->gross_amount ?? $tx->amount ?? 0, 0, ',', '.') }}</td>
                            <td class="text-xs text-success">{{ ($tx->voucher_discount ?? 0) > 0 ? '- Rp ' . number_format($tx->voucher_discount, 0, ',', '.') : '—' }}</td>
                            <td class="font-bold text-sm text-primary">Rp {{ number_format($tx->net_amount ?? 0, 0, ',', '.') }}</td>
                            <td style="text-align:right;">
                                <a href="{{ route('admin.transactions.show', $tx->id) }}" class="btn btn-ghost btn-sm" title="Lihat Detail Transaksi">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted" style="padding:40px;">
                                Tidak ada transaksi yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
        <div class="card-footer">
            {{ $transactions->links() }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleDateCustom(val) {
    const start = document.getElementById('custom-date-start');
    const end = document.getElementById('custom-date-end');
    if (val === 'custom') {
        if (start) start.style.display = 'block';
        if (end) end.style.display = 'block';
    } else {
        if (start) start.style.display = 'none';
        if (end) end.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Time Series Trend Chart
    const trendCtx = document.getElementById('paymentTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Midtrans Gateway (IDR)',
                        data: @json($chartData['midtrans']),
                        backgroundColor: 'rgba(114, 9, 183, 0.7)',
                        borderColor: '#7209b7',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Transfer Bank Manual (IDR)',
                        data: @json($chartData['manual']),
                        backgroundColor: 'rgba(245, 158, 11, 0.7)',
                        borderColor: '#f59e0b',
                        borderWidth: 1,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: false,
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Share Doughnut Chart
    const shareCtx = document.getElementById('paymentShareChart');
    if (shareCtx) {
        new Chart(shareCtx, {
            type: 'doughnut',
            data: {
                labels: ['Midtrans Gateway', 'Transfer Bank Manual'],
                datasets: [{
                    data: [{{ $stats['midtrans_net_revenue'] }}, {{ $stats['manual_net_revenue'] }}],
                    backgroundColor: ['#7209b7', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12 }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }
});
</script>
@endpush
