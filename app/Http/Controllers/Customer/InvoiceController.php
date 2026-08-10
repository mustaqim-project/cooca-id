<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


/**
 * Customer Invoice Controller
 *
 * Manages customer invoices.
 */
class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(Request $request)
    {
        $customer = Auth::user();

        $query = Invoice::where('customer_id', $customer->id)
            ->with(['transaction', 'transaction.subscription.subscriptionPlan.product'])
            ->latest('created_at');

        // Filters
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($year = $request->get('year')) {
            $query->whereYear('created_at', $year);
        }

        $invoices = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Invoice::where('customer_id', $customer->id)->count(),
            'paid' => Invoice::where('customer_id', $customer->id)->where('status', 'paid')->count(),
            'unpaid' => Invoice::where('customer_id', $customer->id)->whereIn('status', ['unpaid', 'issued', 'overdue'])->count(),
            'overdue' => Invoice::where('customer_id', $customer->id)
                ->whereIn('status', ['unpaid', 'issued', 'overdue'])
                ->where('due_at', '<', now())
                ->count(),
        ];

        return view('customer.invoices.index', [
            'invoices' => $invoices,
            'stats' => $stats,
            'filters' => [
                'status' => $request->get('status'),
                'year' => $request->get('year'),
            ],
        ]);
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $customer = Auth::user();

        // Use Policy for authorization (prevents IDOR)
        Gate::authorize('view', $invoice);

        $invoice->load(['transaction', 'transaction.subscription.subscriptionPlan.product']);

        return view('customer.invoices.show', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Download the specified invoice as PDF.
     */
    public function download(Invoice $invoice)
    {
        $customer = Auth::user();

        // Use Policy for authorization (prevents IDOR)
        Gate::authorize('download', $invoice);

        // Use the InvoiceService to generate or retrieve the invoice PDF
        $invoiceService = app(\App\Services\Invoice\InvoiceService::class);
        $pdfPath = $invoiceService->generateInvoicePdf($invoice);

        $fullPath = storage_path('app/' . ltrim($pdfPath, '/'));

        if (!file_exists($fullPath)) {
            abort(500, 'Invoice file not found');
        }

        return response()->download($fullPath, "invoice-{$invoice->invoice_number}.pdf", [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
