@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('admin.accounting.journal.index') }}" class="btn btn-ghost" style="padding: 0.5rem; border: 1px solid var(--border);">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="page-title">Buat Jurnal Baru</h1>
            <p class="page-subtitle">Catat transaksi manual debit & kredit.</p>
        </div>
    </div>
</div>

    <form action="{{ route('admin.accounting.journal.store') }}" method="POST" class="card" id="journalForm">
        @csrf
        
        <!-- Header Info -->
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Tanggal Jurnal</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="form-control">
                </div>
                <div>
                    <label class="form-label">Nomor Referensi (Opsional)</label>
                    <input type="text" name="reference" placeholder="Contoh: INV-001" class="form-control">
                </div>
                <div style="grid-column: span 2;">
                    <label class="form-label">Deskripsi Umum</label>
                    <textarea name="description" rows="2" placeholder="Catatan jurnal..." class="form-control"></textarea>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1rem;">
                <h3 style="font-weight: bold; margin: 0;">Detail Akun</h3>
                <button type="button" onclick="addJournalRow()" class="btn btn-ghost btn-sm">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Baris
                </button>
            </div>

            <div class="data-table-wrap">
                <table class="data-table" id="journalTable">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Akun (Chart of Account)</th>
                            <th style="width: 20%;">Debit</th>
                            <th style="width: 20%;">Kredit</th>
                            <th style="width: 10%;"></th>
                        </tr>
                    </thead>
                    <tbody id="journalBody">
                        <!-- Initial Row 1 -->
                        <tr>
                            <td>
                                <select name="accounts[]" required class="form-select">
                                    <option value="">Pilih Akun...</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">[{{ $acc->code }}] {{ $acc->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="debits[]" min="0" value="0" step="0.01" onchange="calculateTotals()" class="form-control journal-debit" style="font-family: monospace;">
                            </td>
                            <td>
                                <input type="number" name="credits[]" min="0" value="0" step="0.01" onchange="calculateTotals()" class="form-control journal-credit" style="font-family: monospace;">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" onclick="this.closest('tr').remove(); calculateTotals();" class="btn btn-ghost btn-sm text-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Initial Row 2 -->
                        <tr>
                            <td>
                                <select name="accounts[]" required class="form-select">
                                    <option value="">Pilih Akun...</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">[{{ $acc->code }}] {{ $acc->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="debits[]" min="0" value="0" step="0.01" onchange="calculateTotals()" class="form-control journal-debit" style="font-family: monospace;">
                            </td>
                            <td>
                                <input type="number" name="credits[]" min="0" value="0" step="0.01" onchange="calculateTotals()" class="form-control journal-credit" style="font-family: monospace;">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" onclick="this.closest('tr').remove(); calculateTotals();" class="btn btn-ghost btn-sm text-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="border-top: 2px solid var(--border);">
                            <td style="text-align: right; font-weight: bold; padding: 12px;">Total:</td>
                            <td style="font-family: monospace; font-weight: bold; padding: 12px;" id="totalDebit">0.00</td>
                            <td style="font-family: monospace; font-weight: bold; padding: 12px;" id="totalCredit">0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <p id="balanceWarning" style="display: none; color: var(--danger); font-size: 0.875rem; font-weight: 500; margin-top: 0.5rem;"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Total Debit dan Kredit harus seimbang (Balance).</p>
        </div>

        <div class="card-footer" style="text-align: right;">
            <button type="submit" id="btnSubmit" class="btn btn-primary" disabled style="opacity: 0.5; pointer-events: none;">
                Simpan Jurnal
            </button>
        </div>
    </form>
</div>

<!-- Template for new row -->
<template id="rowTemplate">
    <tr>
        <td>
            <select name="accounts[]" required class="form-select">
                <option value="">Pilih Akun...</option>
                @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}">[{{ $acc->code }}] {{ $acc->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="debits[]" min="0" value="0" step="0.01" onchange="calculateTotals()" class="form-control journal-debit" style="font-family: monospace;">
        </td>
        <td>
            <input type="number" name="credits[]" min="0" value="0" step="0.01" onchange="calculateTotals()" class="form-control journal-credit" style="font-family: monospace;">
        </td>
        <td style="text-align: center;">
            <button type="button" onclick="this.closest('tr').remove(); calculateTotals();" class="btn btn-ghost btn-sm text-danger">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    function addJournalRow() {
        const template = document.getElementById('rowTemplate');
        const tbody = document.getElementById('journalBody');
        const clone = template.content.cloneNode(true);
        tbody.appendChild(clone);
    }

    function calculateTotals() {
        let debits = 0;
        let credits = 0;

        document.querySelectorAll('.journal-debit').forEach(el => {
            debits += parseFloat(el.value || 0);
        });

        document.querySelectorAll('.journal-credit').forEach(el => {
            credits += parseFloat(el.value || 0);
        });

        document.getElementById('totalDebit').innerText = debits.toFixed(2);
        document.getElementById('totalCredit').innerText = credits.toFixed(2);

        const btnSubmit = document.getElementById('btnSubmit');
        const warning = document.getElementById('balanceWarning');

        if(debits > 0 && credits > 0 && debits === credits) {
            btnSubmit.disabled = false;
            btnSubmit.style.opacity = '1';
            btnSubmit.style.pointerEvents = 'auto';
            warning.style.display = 'none';
        } else {
            btnSubmit.disabled = true;
            btnSubmit.style.opacity = '0.5';
            btnSubmit.style.pointerEvents = 'none';
            warning.style.display = 'block';
        }
    }
</script>
@endsection
