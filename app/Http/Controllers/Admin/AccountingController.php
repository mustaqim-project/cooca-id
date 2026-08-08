<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\ChartOfAccountType;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    // --- Chart of Accounts ---
    public function coaIndex()
    {
        $accounts = ChartOfAccount::with(['typeAccount', 'subTypeAccount'])->get();
        return view('admin.finance.coa.index', compact('accounts'));
    }

    public function coaStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|integer',
        ]);

        ChartOfAccount::create($request->all());
        return back()->with('success', 'Akun berhasil ditambahkan.');
    }

    // --- Journal Entries ---
    public function journalIndex()
    {
        $journals = JournalEntry::with('items.accountObj')->orderBy('date', 'desc')->paginate(20);
        return view('admin.finance.journal.index', compact('journals'));
    }

    public function journalCreate()
    {
        $accounts = ChartOfAccount::all();
        return view('admin.finance.journal.create', compact('accounts'));
    }

    public function journalStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'reference' => 'nullable|string',
            'description' => 'nullable|string',
            'accounts' => 'required|array',
            'debits' => 'required|array',
            'credits' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $journal = JournalEntry::create([
                'date' => $request->date,
                'reference' => $request->reference,
                'description' => $request->description,
            ]);

            foreach ($request->accounts as $index => $accountId) {
                if ($accountId) {
                    JournalItem::create([
                        'journal' => $journal->id,
                        'account' => $accountId,
                        'debit' => $request->debits[$index] ?? 0,
                        'credit' => $request->credits[$index] ?? 0,
                        'description' => $request->description,
                    ]);
                }
            }
        });

        return redirect()->route('finance.journal.index')->with('success', 'Jurnal berhasil ditambahkan.');
    }

    // --- Reports ---
    public function reportLedger(Request $request)
    {
        $accountId = $request->get('account_id');
        $accounts = ChartOfAccount::all();
        $ledger = [];

        if ($accountId) {
            $ledger = JournalItem::with('journalEntry')
                ->where('account', $accountId)
                ->join('journal_entries', 'journal_items.journal', '=', 'journal_entries.id')
                ->orderBy('journal_entries.date', 'asc')
                ->get();
        }

        return view('admin.finance.reports.ledger', compact('accounts', 'ledger', 'accountId'));
    }

    public function reportProfitLoss(Request $request)
    {
        // Simple P&L calculation (Revenue accounts credit - debit, Expense accounts debit - credit)
        // This assumes account types: Revenue (type id = e.g. 4), Expense (type id = e.g. 5)
        
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $revenues = ChartOfAccount::whereHas('typeAccount', function($q){
                $q->where('name', 'like', '%Revenue%')->orWhere('name', 'like', '%Pendapatan%');
            })
            ->with(['journalItems' => function($q) use ($month, $year) {
                $q->join('journal_entries', 'journal_items.journal', '=', 'journal_entries.id')
                  ->whereMonth('journal_entries.date', $month)
                  ->whereYear('journal_entries.date', $year);
            }])->get();

        $expenses = ChartOfAccount::whereHas('typeAccount', function($q){
                $q->where('name', 'like', '%Expense%')->orWhere('name', 'like', '%Beban%');
            })
            ->with(['journalItems' => function($q) use ($month, $year) {
                $q->join('journal_entries', 'journal_items.journal', '=', 'journal_entries.id')
                  ->whereMonth('journal_entries.date', $month)
                  ->whereYear('journal_entries.date', $year);
            }])->get();

        return view('admin.finance.reports.profit_loss', compact('revenues', 'expenses', 'month', 'year'));
    }
}
