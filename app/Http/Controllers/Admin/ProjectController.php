<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Customer;
use App\Models\Contract;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of custom development projects.
     */
    public function index()
    {
        $projects = Project::with(['customer', 'contract'])->latest()->get();
        $customers = Customer::orderBy('name')->get();
        $contracts = Contract::with('customer')->latest()->get();

        return view('admin.projects.index', compact('projects', 'customers', 'contracts'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $contracts = Contract::with('customer')->latest()->get();
        return view('admin.projects.create', compact('customers', 'contracts'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'customer_id' => 'nullable|uuid|exists:customers,id',
            'contract_id' => 'nullable|uuid|exists:contracts,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:pending,in_progress,completed,on_hold,cancelled',
            'estimated_hrs' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'agreement_document' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
        ]);

        if ($request->hasFile('agreement_document')) {
            $validated['agreement_document'] = '/' . $request->file('agreement_document')->store('agreements', 'public_uploads');
        }

        $validated['created_by'] = auth('admin')->id();

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dibuat.');
    }

    /**
     * Display the specified project.
     */
    public function show(string $id)
    {
        $project = Project::with(['customer', 'contract', 'tasks', 'transactions.invoice'])->findOrFail($id);
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        $contracts = Contract::with('customer')->latest()->get();
        return view('admin.projects.edit', compact('project', 'customers', 'contracts'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'project_name' => 'sometimes|required|string|max:255',
            'customer_id' => 'nullable|uuid|exists:customers,id',
            'contract_id' => 'nullable|uuid|exists:contracts,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|string|in:pending,in_progress,completed,on_hold,cancelled',
            'estimated_hrs' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'agreement_document' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
        ]);

        if ($request->hasFile('agreement_document')) {
            // Delete old file if exists
            if ($project->agreement_document) {
                $oldPath = public_path(ltrim($project->agreement_document, '/'));
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            $validated['agreement_document'] = '/' . $request->file('agreement_document')->store('agreements', 'public_uploads');
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);

        if ($project->agreement_document) {
            $oldPath = public_path(ltrim($project->agreement_document, '/'));
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dihapus.');
    }

    /**
     * Create billing/payment link for the project.
     */
    public function createBilling(Request $request, string $id, PaymentService $paymentService)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($project, $validated, $paymentService) {
                $yearMonth = now()->format('Ym');
                $lastTxn = Transaction::where('invoice_number', 'like', "INV/{$yearMonth}%")
                    ->orderBy('invoice_number', 'desc')
                    ->lockForUpdate()
                    ->first();
                $lastNum = $lastTxn ? (int) substr($lastTxn->invoice_number, -5) : 0;
                $invoiceNumber = "INV/{$yearMonth}/" . str_pad((string) ($lastNum + 1), 5, '0', STR_PAD_LEFT);

                $transaction = Transaction::create([
                    'customer_id'      => $project->customer_id,
                    'project_id'       => $project->id,
                    'description'      => $validated['description'],
                    'type'             => 'project_payment',
                    'invoice_number'   => $invoiceNumber,
                    'gross_amount'     => $validated['amount'],
                    'net_amount'       => $validated['amount'],
                    'payment_method'   => 'midtrans',
                    'payment_gateway'  => 'midtrans',
                    'status'           => 'pending',
                ]);

                Invoice::create([
                    'transaction_id' => $transaction->id,
                    'invoice_number' => $invoiceNumber,
                    'customer_id'    => $project->customer_id,
                    'amount'         => $validated['amount'],
                    'status'         => 'issued',
                    'issued_at'      => now(),
                    'due_at'         => now()->addDays(3),
                ]);

                // Register transaction with Midtrans Snap to set midtrans_order_id inside transaction
                $paymentService->createSnapTransaction($transaction);
            });

            return redirect()->route('admin.projects.show', $project->id)
                ->with('success', 'Link pembayaran project berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('admin.projects.show', $project->id)
                ->with('error', 'Gagal membuat link pembayaran: ' . $e->getMessage());
        }
    }
}
