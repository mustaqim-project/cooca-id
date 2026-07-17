<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * Admin Email Campaign Controller
 *
 * Manages email marketing campaigns.
 */
class EmailCampaignController extends Controller
{
    /**
     * Display a listing of email campaigns.
     */
    public function index(Request $request)
    {
        $query = EmailCampaign::with(['creator'])->latest('created_at');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $campaigns = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => EmailCampaign::count(),
            'draft' => EmailCampaign::where('status', 'draft')->count(),
            'sent' => EmailCampaign::where('status', 'sent')->count(),
            'scheduled' => EmailCampaign::where('status', 'scheduled')->count(),
        ];

        return view('admin.email-campaigns.index', [
            'campaigns' => $campaigns,
            'stats' => $stats,
            'filters' => [
                'status' => $request->get('status'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create()
    {
        $customerCount = Customer::where('is_active', true)->count();

        return view('admin.email-campaigns.create', [
            'campaign' => null,
            'customerCount' => $customerCount,
        ]);
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,segment,specific',
            'segment_filters' => 'nullable|array',
            'recipient_ids' => 'nullable|array',
            'recipient_ids.*' => 'exists:customers,id',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $validated['status'] = $request->filled('scheduled_at') ? 'scheduled' : 'draft';
        $validated['created_by'] = Auth::id();
        $validated['recipient_count'] = 0; // Will be calculated before sending

        $campaign = EmailCampaign::create($validated);

        return redirect()->route('admin.email-campaigns.index')
            ->with('success', 'Email campaign created successfully.');
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit(EmailCampaign $campaign)
    {
        $customerCount = Customer::where('is_active', true)->count();

        return view('admin.email-campaigns.create', [
            'campaign' => $campaign,
            'customerCount' => $customerCount,
        ]);
    }

    /**
     * Update the specified campaign.
     */
    public function update(Request $request, EmailCampaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,segment,specific',
            'segment_filters' => 'nullable|array',
            'recipient_ids' => 'nullable|array',
            'recipient_ids.*' => 'exists:customers,id',
            'scheduled_at' => 'nullable|date',
        ]);

        $validated['status'] = $request->filled('scheduled_at') ? 'scheduled' : 'draft';

        $campaign->update($validated);

        return redirect()->route('admin.email-campaigns.index')
            ->with('success', 'Email campaign updated successfully.');
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(EmailCampaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('admin.email-campaigns.index')
            ->with('success', 'Email campaign deleted successfully.');
    }

    /**
     * Display the specified campaign.
     */
    public function show(EmailCampaign $campaign)
    {
        $campaign->load(['creator']);

        return view('admin.email-campaigns.show', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Send the specified campaign.
     */
    public function send(EmailCampaign $campaign, Request $request)
    {
        if ($campaign->status === 'sent') {
            return back()->with('error', 'Campaign has already been sent.');
        }

        // Calculate recipients
        $recipients = [];

        if ($campaign->recipient_type === 'all') {
            $recipients = Customer::where('is_active', true)
                ->whereNotNull('email')
                ->pluck('email');
        } elseif ($campaign->recipient_type === 'segment' && $campaign->segment_filters) {
            // Apply segment filters
            $query = Customer::where('is_active', true)->whereNotNull('email');

            foreach ($campaign->segment_filters as $filter) {
                if (isset($filter['field']) && isset($filter['operator']) && isset($filter['value'])) {
                    $query->where($filter['field'], $filter['operator'], $filter['value']);
                }
            }

            $recipients = $query->pluck('email');
        } elseif ($campaign->recipient_type === 'specific' && $campaign->recipient_ids) {
            $recipients = Customer::whereIn('id', $campaign->recipient_ids)
                ->whereNotNull('email')
                ->pluck('email');
        }

        // Update campaign with recipient count
        $campaign->update([
            'recipient_count' => $recipients->count(),
            'status' => 'sending',
        ]);

        // Dispatch job to send emails
        \App\Jobs\SendEmailCampaignJob::dispatch($campaign, $recipients);

        return back()->with('success', 'Email campaign is being sent.');
    }
}
