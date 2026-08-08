<?php

declare(strict_types=1);

namespace App\Services\License;

use App\Models\ErpRequest;
use App\Models\License;
use App\Models\LicenseLog;
use App\Models\Subscription;
use App\Models\Domain;
use App\Services\Notification\NotificationService;
use App\Notifications\Customer\TrialActivatedNotification;
use App\Services\Notification\WhatsAppService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class TrialActivationService
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {}

    public function activateTrial(ErpRequest $erpRequest, int $trialDays = 14): License
    {
        return DB::transaction(function () use ($erpRequest, $trialDays) {
            $trialStartsAt = $erpRequest->trial_starts_at ?? now();
            $trialEndsAt = $erpRequest->trial_ends_at ?? now()->addDays($trialDays);

            $domain = $this->getOrCreateDomain($erpRequest);
            $licenseCode = $this->generateUniqueCode();
            $tokenCode = $this->generateUniqueCode();

            $license = License::create([
                'customer_id' => $erpRequest->customer_id,
                'product_id' => $erpRequest->product_id,
                'subscription_plan_id' => $this->getTrialPlanId($erpRequest->product_id),
                'erp_request_id' => $erpRequest->id,
                'domain_id' => $domain->id,
                'license_code' => $licenseCode,
                'token_code' => $tokenCode,
                'domain' => $domain->domain,
                'status' => License::STATUS_ACTIVE,
                'is_trial' => true,
                'activated_at' => $trialStartsAt,
                'starts_at' => $trialStartsAt,
                'expires_at' => $trialEndsAt,
            ]);

            LicenseLog::log(
                $license->id,
                LicenseLog::ACTION_GENERATED,
                'License generated for trial activation',
                ['erp_request_id' => $erpRequest->id, 'trial_days' => $trialDays],
                request()->ip(),
                request()->userAgent()
            );

            Subscription::create([
                'customer_id' => $erpRequest->customer_id,
                'license_id' => $license->id,
                'subscription_plan_id' => $license->subscription_plan_id,
                'status' => Subscription::STATUS_ACTIVE,
                'started_at' => $trialStartsAt,
                'expires_at' => $trialEndsAt,
            ]);

            // Link subscription to license
            $license->subscription_id = Subscription::where('license_id', $license->id)
                ->latest('created_at')
                ->first()?->id;
            $license->save();

            $erpRequest->activateTrial($trialStartsAt, $trialEndsAt);
            $domain->markAsActive();
            $this->logActivity($erpRequest, $license);
            $this->sendNotifications($erpRequest, $license, $trialEndsAt);

            return $license;
        });
    }

    private function getOrCreateDomain(ErpRequest $erpRequest): Domain
    {
        $existingDomain = Domain::where('erp_request_id', $erpRequest->id)->first();
        if ($existingDomain) {
            return $existingDomain;
        }

        $domainValue = $erpRequest->requested_domain ?? 
                       ($erpRequest->requested_subdomain ? $erpRequest->requested_subdomain . '.cooca.id' : 'trial-' . Str::random(8) . '.cooca.id');

        // Check if the domain name already exists in the database to prevent duplicate entry exception
        $duplicateDomain = Domain::where('domain', $domainValue)->first();
        if ($duplicateDomain) {
            // If it belongs to the same customer or has no request linked, reuse it
            if ($duplicateDomain->customer_id === $erpRequest->customer_id || empty($duplicateDomain->erp_request_id)) {
                $duplicateDomain->update([
                    'erp_request_id' => $erpRequest->id,
                    'status' => Domain::STATUS_ACTIVE,
                ]);
                return $duplicateDomain;
            }
            
            // Otherwise, since the domain is taken by another customer, append a random suffix to make it unique
            $domainValue = $erpRequest->requested_subdomain . '-' . Str::lower(Str::random(4)) . '.cooca.id';
        }

        return Domain::create([
            'customer_id' => $erpRequest->customer_id,
            'erp_request_id' => $erpRequest->id,
            'domain' => $domainValue,
            'type' => str_contains($domainValue, 'cooca.id') ? Domain::TYPE_SUBDOMAIN : Domain::TYPE_CUSTOM_DOMAIN,
            'status' => Domain::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);
    }

    private function getTrialPlanId(string $productId): string
    {
        $plan = \App\Models\SubscriptionPlan::where('product_id', $productId)
            ->where('price', 0)
            ->first();
        
        if (!$plan) {
            $plan = \App\Models\SubscriptionPlan::create([
                'product_id' => $productId,
                'name' => 'Trial Plan',
                'price' => 0,
                'duration_months' => 0,
                'is_active' => true,
            ]);
        }

        return $plan->id;
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(16));
        } while (License::where('license_code', $code)->exists());
        return $code;
    }

    private function logActivity(ErpRequest $erpRequest, License $license): void
    {
        \App\Models\ActivityLog::create([
            'causer_id' => auth()->id(),
            'causer_type' => \App\Models\Customer::class,
            'action' => 'trial_activated',
            'module' => 'erp_request',
            'description' => "Trial activated for customer {$erpRequest->customer->email}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => [
                'erp_request_id' => $erpRequest->id,
                'customer_id' => (string) $erpRequest->customer_id,
                'license_id' => $license->id,
                'license_code' => $license->license_code,
            ],
        ]);
    }

    private function sendNotifications(ErpRequest $erpRequest, License $license, \DateTimeInterface $trialEndsAt): void
    {
        $customer = $erpRequest->customer;

        // Send email and DB notification
        $customer->notify(new TrialActivatedNotification(
            $license,
            $trialEndsAt,
            $erpRequest->admin_notes
        ));

        // Send WhatsApp notification
        if (!empty($customer->phone)) {
            $whatsappService = app(WhatsAppService::class);
            $whatsappService->send(
                $customer->phone,
                "🎉 Trial Activated!\n\nYour COOCA.ID ERP is ready.\nDomain: {$license->domain}\nLicense: {$license->license_code}\nTrial ends: {$trialEndsAt->format('d M Y')}\n\nLogin: " . route('customer.login')
            );
        }
    }
}


