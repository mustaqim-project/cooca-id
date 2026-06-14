<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

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
        $customer = Auth::guard('customer')->user();

        $query = Invoice::where('customer_id', $customer->id)
            ->with(['subscription', 'transaction'])
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
            'unpaid' => Invoice::where('customer_id', $customer->id)->where('status', 'unpaid')->count(),
            'overdue' => Invoice::where('customer_id', $customer->id)
                ->where('status', 'unpaid')
                ->where('due_date', '<', now())
                ->count(),
        ];

        return Inertia::render('Customer/Invoices/Index', [
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
        $customer = Auth::guard('customer')->user();

        // Use Policy for authorization (prevents IDOR)
        Gate::authorize('view', $invoice);

        $invoice->load(['subscription.product', 'transaction']);

        return Inertia::render('Customer/Invoices/Show', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Download the specified invoice as PDF.
     */
    public function download(Invoice $invoice)
    {
        $customer = Auth::guard('customer')->user();

        // Use Policy for authorization (prevents IDOR)
        Gate::authorize('download', $invoice);

        // Generate PDF (implementation depends on PDF library used)
        // For now, we'll return a response that would trigger PDF generation
        
        $pdf = \App\Services\InvoicePdfService::generate($invoice);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, "invoice-{$invoice->invoice_number}.pdf", [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
