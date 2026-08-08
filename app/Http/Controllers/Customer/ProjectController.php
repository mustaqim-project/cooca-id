<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class ProjectController extends Controller
{
    /**
     * Display a listing of projects belonging to the authenticated customer.
     */
    public function index()
    {
        $projects = Project::where('customer_id', Auth::id())
            ->withCount('tasks')
            ->latest()
            ->get();

        return view('customer.projects.index', compact('projects'));
    }

    /**
     * Display the specified project.
     */
    public function show(string $id)
    {
        $project = Project::where('customer_id', Auth::id())
            ->with(['tasks', 'transactions.invoice'])
            ->findOrFail($id);

        return view('customer.projects.show', compact('project'));
    }

    /**
     * Show the checkout page for project payment.
     */
    public function checkout(string $projectId, string $invoiceId, PaymentService $paymentService)
    {
        $project = Project::where('customer_id', Auth::id())->findOrFail($projectId);
        $invoice = Invoice::where('id', $invoiceId)
            ->where('customer_id', Auth::id())
            ->with('transaction')
            ->firstOrFail();

        if ($invoice->transaction->project_id !== $project->id) {
            abort(400, 'Invoice ini tidak terasosiasi dengan project ini.');
        }

        $transaction = $invoice->transaction;
        $snapToken = null;
        $snapUrl = null;

        if ($invoice->status !== 'paid') {
            try {
                $snapData = $paymentService->createSnapTransaction($transaction);
                $snapToken = $snapData['snap_token'] ?? null;
                $snapUrl = $snapData['snap_url'] ?? null;
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal memproses transaksi ke Payment Gateway: ' . $e->getMessage());
            }
        }

        return view('customer.projects.checkout', compact('project', 'invoice', 'transaction', 'snapToken', 'snapUrl'));
    }
}
