<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trial;
use App\Services\Trial\TrialManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TrialManagementController extends Controller
{
    public function __construct(
        private readonly TrialManagementService $trialService
    ) {}

    /**
     * Display a listing of trials.
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'status' => ['nullable', 'string'],
            'product_id' => ['nullable', 'string'],
            'customer_email' => ['nullable', 'email'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'sort_by' => ['nullable', Rule::in(['created_at', 'submitted_at', 'expires_at'])],
            'sort_order' => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        $query = Trial::with(['customer.user', 'erpProduct', 'subscriptionPlan']);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['product_id'])) {
            $query->where('erp_product_id', $filters['product_id']);
        }

        if (isset($filters['customer_email'])) {
            $query->whereHas('customer.user', function ($q) use ($filters) {
                $q->where('email', 'like', '%' . $filters['customer_email'] . '%');
            });
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'submitted_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $trials = $query->paginate(20)->withQueryString();

        return view('admin.trials.index', compact('trials', 'filters'));
    }

    /**
     * Display the specified trial.
     */
    public function show(string $id)
    {
        $trial = Trial::with([
            'customer.user',
            'erpProduct',
            'subscriptionPlan',
            'affiliateCode.affiliator.user',
            'statusHistory.actor'
        ])->findOrFail($id);

        return view('admin.trials.show', compact('trial'));
    }

    /**
     * Approve trial request.
     */
    public function approve(string $id, Request $request)
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $adminId = Auth::user()->id;
            $trial = $this->trialService->approveTrial($id, $adminId);

            return redirect()->route('admin.trials.show', $trial->id)
                ->with('success', 'Trial approved successfully. Provisioning started.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve trial: ' . $e->getMessage());
        }
    }

    /**
     * Reject trial request.
     */
    public function reject(string $id, Request $request)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        try {
            $adminId = Auth::user()->id;
            $trial = $this->trialService->rejectTrial($id, $adminId, $validated['rejection_reason']);

            return redirect()->route('admin.trials.show', $trial->id)
                ->with('success', 'Trial rejected successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject trial: ' . $e->getMessage());
        }
    }

    /**
     * Mark trial as domain setup phase.
     */
    public function markDomainSetup(string $id)
    {
        try {
            $trial = $this->trialService->markAsDomainSetup($id);

            return redirect()->route('admin.trials.show', $trial->id)
                ->with('success', 'Trial marked as domain setup phase.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Mark trial as testing phase.
     */
    public function markTesting(string $id)
    {
        try {
            $trial = $this->trialService->markAsTesting($id);

            return redirect()->route('admin.trials.show', $trial->id)
                ->with('success', 'Trial marked as testing phase.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Start trial period manually (if auto-start failed).
     */
    public function startTrial(string $id, Request $request)
    {
        $validated = $request->validate([
            'duration_days' => ['nullable', 'integer', 'min:1', 'max:30'],
        ]);

        try {
            $durationDays = $validated['duration_days'] ?? 14;
            $trial = $this->trialService->startTrialPeriod($id, $durationDays);

            return redirect()->route('admin.trials.show', $trial->id)
                ->with('success', "Trial period started for {$durationDays} days.");
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to start trial: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for dashboard.
     */
    public function stats()
    {
        $stats = [
            'total' => Trial::count(),
            'waiting_approval' => Trial::where('status', Trial::STATUS_WAITING_APPROVAL)->count(),
            'provisioning' => Trial::whereIn('status', [
                Trial::STATUS_WAITING_PROVISIONING,
                Trial::STATUS_PROVISIONING,
                Trial::STATUS_DOMAIN_SETUP,
                Trial::STATUS_TESTING,
            ])->count(),
            'active_trial' => Trial::where('status', Trial::STATUS_ACTIVE_TRIAL)->count(),
            'converted' => Trial::where('status', Trial::STATUS_CONVERTED_TO_SUBSCRIPTION)->count(),
            'expired' => Trial::where('status', Trial::STATUS_EXPIRED)->count(),
            'rejected' => Trial::where('status', Trial::STATUS_REJECTED)->count(),
            'failed' => Trial::where('status', Trial::STATUS_FAILED)->count(),
        ];

        return response()->json($stats);
    }
}
