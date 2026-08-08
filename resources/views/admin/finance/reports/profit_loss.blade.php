@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.finance.index') }}">Finance</a>
            <span>/</span>
            <span>Laba & Rugi</span>
        </div>
        <h1 class="page-title">Laba & Rugi (Profit & Loss)</h1>
        <p class="page-subtitle">Ringkasan pendapatan dan beban bulan ini.</p>
    </div>
</div>

    <!-- Filter -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <form action="{{ route('admin.accounting.reports.profit-loss') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
                <div>
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="form-label">Tahun</label>
                    <select name="year" class="form-select">
                        @for($i = date('Y') - 2; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="margin-top: 1rem;">
        <!-- PENDAPATAN (REVENUE) -->
        <div class="card">
            <div class="card-header" style="background: rgba(var(--success-rgb), 0.1); border-bottom-color: rgba(var(--success-rgb), 0.2);">
                <h3 class="card-title" style="color: var(--success); margin: 0;">Pendapatan (Revenue)</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="table">
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php $totalRevenue = 0; @endphp
                        @forelse($revenues as $rev)
                            @php 
                                $accRev = $rev->journalItems->sum('credit') - $rev->journalItems->sum('debit');
                                $totalRevenue += $accRev;
                            @endphp
                            @if($accRev != 0)
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 dark:text-gray-400">[{{ $rev->code }}] {{ $rev->name }}</td>
                                    <td class="py-2 text-sm font-mono text-right text-gray-900 dark:text-white">Rp {{ number_format($accRev, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 1rem;">Tidak ada pendapatan bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr style="border-top: 2px solid var(--border);">
                            <td style="font-weight: bold; padding: 12px 16px;">Total Pendapatan</td>
                            <td style="font-family: monospace; font-weight: bold; text-align: right; color: var(--success); padding: 12px 16px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- BEBAN (EXPENSE) -->
        <div class="card">
            <div class="card-header" style="background: rgba(var(--danger-rgb), 0.1); border-bottom-color: rgba(var(--danger-rgb), 0.2);">
                <h3 class="card-title" style="color: var(--danger); margin: 0;">Beban Operasional (Expense)</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="table">
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php $totalExpense = 0; @endphp
                        @forelse($expenses as $exp)
                            @php 
                                $accExp = $exp->journalItems->sum('debit') - $exp->journalItems->sum('credit');
                                $totalExpense += $accExp;
                            @endphp
                            @if($accExp != 0)
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 dark:text-gray-400">[{{ $exp->code }}] {{ $exp->name }}</td>
                                    <td class="py-2 text-sm font-mono text-right text-gray-900 dark:text-white">Rp {{ number_format($accExp, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 1rem;">Tidak ada beban bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr style="border-top: 2px solid var(--border);">
                            <td style="font-weight: bold; padding: 12px 16px;">Total Beban</td>
                            <td style="font-family: monospace; font-weight: bold; text-align: right; color: var(--danger); padding: 12px 16px;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- NET PROFIT -->
    <div class="card" style="margin-top: 1.5rem; background: var(--surface); text-align: center; padding: 2rem;">
        <p class="text-muted" style="font-weight: 500; margin-bottom: 0.5rem;">Laba Bersih (Net Profit)</p>
        @php $netProfit = $totalRevenue - $totalExpense; @endphp
        <h2 style="font-family: monospace; font-size: 2.5rem; font-weight: bold; margin: 0; color: {{ $netProfit >= 0 ? 'var(--success)' : 'var(--danger)' }}">
            {{ $netProfit >= 0 ? '+' : '-' }} Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
        </h2>
    </div>
@endsection
