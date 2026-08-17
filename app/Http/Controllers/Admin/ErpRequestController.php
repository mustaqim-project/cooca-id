<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErpRequest;
use App\Services\License\TrialActivationService;
use Illuminate\Http\Request;



final class ErpRequestController extends Controller
{
    public function __construct(
        private readonly TrialActivationService $trialActivationService,
    ) {}

    private function authorizeManagement(): void
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin) {
            abort(401, 'Unauthenticated.');
        }

        $roles = $admin->roles->pluck('name')->toArray();
        $permissions = $admin->getAllPermissions()->pluck('name')->toArray();

        if (!in_array('manage_erp_requests', $permissions) && !in_array('super_admin', $roles)) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index(Request $request)
    {
        $query = ErpRequest::with(['customer', 'product', 'affiliator', 'approvedBy']);

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('requested_subdomain', 'like', "%{$search}%")
                  ->orWhere('requested_domain', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => ErpRequest::count(),
            'waiting_approval' => ErpRequest::whereIn('status', ['submitted', 'waiting_approval'])->count(),
            'in_progress' => ErpRequest::whereIn('status', ['waiting_setup', 'in_setup', 'domain_setup', 'testing'])->count(),
            'active_trial' => ErpRequest::where('status', 'active_trial')->count(),
        ];

        return view('admin.erprequests.index', [
            'requests' => $requests,
            'stats' => $stats,
        ]);
    }

    public function show(ErpRequest $erpRequest)
    {
        $erpRequest->load(['customer', 'product', 'affiliator', 'approvedBy', 'domains', 'license']);

        return view('admin.erprequests.show', [
            'request' => $erpRequest,
        ]);
    }

    public function approve(Request $request, ErpRequest $erpRequest)
    {
        $this->authorizeManagement();

        if (!in_array($erpRequest->status, ['submitted', 'waiting_approval'])) {
            return redirect()->back()->with('error', 'ERP request has already been processed.');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $adminId = auth()->id();
        
        $now = now();
        $erpRequest->update([
            'approved_by' => $adminId,
            'approved_at' => $now,
            'admin_notes' => $validated['admin_notes'],
            'trial_starts_at' => $now,
            'trial_ends_at' => $now->copy()->addDays(14),
        ]);

        // Immediate trial activation
        $this->trialActivationService->activateTrial($erpRequest, 14);

        \App\Models\ActivityLog::create([
            'causer_id' => $adminId,
            'causer_type' => \App\Models\Admin::class,
            'action' => 'erp_approved',
            'module' => 'erp_request',
            'description' => "ERP request approved for customer " . ($erpRequest->customer?->email ?? 'unknown'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['erp_request_id' => $erpRequest->id],
        ]);

        return redirect()->back()->with('success', 'ERP request approved successfully.');
    }

    public function reject(Request $request, ErpRequest $erpRequest)
    {
        $this->authorizeManagement();
        if (!in_array($erpRequest->status, ['submitted', 'waiting_approval'])) {
            return redirect()->back()->with('error', 'ERP request has already been processed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $erpRequest->update([
            'admin_notes' => $validated['rejection_reason'],
        ]);

        $erpRequest->reject();

        \App\Models\ActivityLog::create([
            'causer_id' => auth()->id(),
            'causer_type' => \App\Models\Admin::class,
            'action' => 'erp_rejected',
            'module' => 'erp_request',
            'description' => "ERP request rejected for customer " . ($erpRequest->customer?->email ?? 'unknown'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'erp_request_id' => $erpRequest->id,
                'reason' => $validated['rejection_reason'],
            ],
        ]);

        return redirect()->back()->with('success', 'ERP request rejected.');
    }

    public function markWaitingSetup(ErpRequest $erpRequest)
    {
        $this->authorizeManagement();
        $erpRequest->update(['status' => ErpRequest::STATUS_WAITING_SETUP]);

        return redirect()->back()->with('success', 'Status updated to Waiting Setup.');
    }

    public function markInSetup(Request $request, ErpRequest $erpRequest)
    {
        $this->authorizeManagement();
        $erpRequest->markInSetup();

        \App\Models\ActivityLog::create([
            'causer_id' => auth()->id(),
            'causer_type' => \App\Models\Admin::class,
            'action' => 'erp_setup_started',
            'module' => 'erp_request',
            'description' => "Setup started for ERP request {$erpRequest->id}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Setup started.');
    }

    public function markDomainSetup(ErpRequest $erpRequest)
    {
        $this->authorizeManagement();
        $erpRequest->markDomainSetup();
        return redirect()->back()->with('success', 'Status updated to Domain Setup.');
    }

    public function markTesting(Request $request, ErpRequest $erpRequest)
    {
        $this->authorizeManagement();
        $erpRequest->markTesting();

        \App\Models\ActivityLog::create([
            'causer_id' => auth()->id(),
            'causer_type' => \App\Models\Admin::class,
            'action' => 'erp_testing',
            'module' => 'erp_request',
            'description' => "Testing started for ERP request {$erpRequest->id}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Status updated to Testing.');
    }

    public function confirmReady(Request $request, ErpRequest $erpRequest)
    {
        $this->authorizeManagement();
        $validated = $request->validate([
            'trial_days' => 'integer|min:1|max:365',
        ]);

        $trialDays = $validated['trial_days'] ?? 14;

        $license = $this->trialActivationService->activateTrial($erpRequest, $trialDays);

        return redirect()->back()->with('success', 'Trial activated! License generated and notifications sent.');
    }
}


