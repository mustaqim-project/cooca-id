<?php

declare(strict_types=1);

namespace App\Services\Provisioning;

use App\Models\ProvisioningJob as ProvJob;
use App\Models\Trial;
use App\Models\Subscription;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Domain;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service layer untuk provisioning tenant
 * Wrapper untuk ProvisioningEngine dengan business logic integration
 */
final class ProvisioningService
{
    public function __construct(
        private readonly ProvisioningEngine $engine
    ) {}

    /**
     * Provision trial tenant
     * Creates provisioning job dan trigger execution
     */
    public function provisionTrial(Trial $trial): ProvJob
    {
        return DB::transaction(function () use ($trial) {
            Log::info("ProvisioningService: Starting trial provisioning for trial {$trial->id}");

            // Get customer and product info
            $customer = $trial->customer;
            $product = $trial->product;

            // Create or get domain record
            $domain = $this->createDomainRecord($trial);

            // Create ERP Request for provisioning
            $erpRequest = $this->createErpRequest($trial, $domain);

            // Create provisioning job
            $job = ProvJob::create([
                'erp_request_id' => $erpRequest->id,
                'tenant_type' => 'trial',
                'tenant_id' => $trial->id,
                'status' => 'pending',
                'current_step' => 'init',
                'attempts' => 0,
                'max_attempts' => 3,
                'metadata' => [
                    'trial_id' => $trial->id,
                    'customer_id' => $trial->customer_id,
                    'product_id' => $trial->erp_product_id,
                    'subdomain' => $trial->subdomain,
                ],
            ]);

            // Update trial status
            $trial->update([
                'status' => Trial::STATUS_PROVISIONING,
            ]);

            // Create status history
            $trial->statusHistories()->create([
                'from_status' => Trial::STATUS_WAITING_PROVISIONING,
                'to_status' => Trial::STATUS_PROVISIONING,
                'notes' => 'Provisioning job created',
                'performed_by' => 'system',
                'performed_by_type' => 'system',
            ]);

            Log::info("ProvisioningService: Provisioning job {$job->id} created for trial {$trial->id}");

            return $job;
        });
    }

    /**
     * Provision subscription tenant (converted from trial or new)
     */
    public function provisionSubscription(Subscription $subscription): ProvJob
    {
        return DB::transaction(function () use ($subscription) {
            Log::info("ProvisioningService: Starting subscription provisioning for subscription {$subscription->id}");

            $customer = $subscription->customer;
            $plan = $subscription->subscriptionPlan;

            // Create or get domain record
            $domain = $this->createDomainRecordForSubscription($subscription);

            // Create ERP Request
            $erpRequest = $this->createErpRequestForSubscription($subscription, $domain);

            // Create provisioning job
            $job = ProvJob::create([
                'erp_request_id' => $erpRequest->id,
                'tenant_type' => 'subscription',
                'tenant_id' => $subscription->id,
                'status' => 'pending',
                'current_step' => 'init',
                'attempts' => 0,
                'max_attempts' => 3,
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'customer_id' => $subscription->customer_id,
                    'plan_id' => $subscription->subscription_plan_id,
                    'subdomain' => $subscription->subdomain,
                ],
            ]);

            Log::info("ProvisioningService: Provisioning job {$job->id} created for subscription {$subscription->id}");

            return $job;
        });
    }

    /**
     * Run provisioning job synchronously
     */
    public function runProvisioning(ProvJob $job): void
    {
        Log::info("ProvisioningService: Running provisioning job {$job->id}");
        
        try {
            $this->engine->run($job);
            
            if ($job->status === 'completed') {
                $this->handleProvisioningSuccess($job);
            } else {
                $this->handleProvisioningFailure($job, 'Provisioning completed with errors');
            }
        } catch (\Exception $e) {
            Log::error("ProvisioningService: Job {$job->id} failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            $this->handleProvisioningFailure($job, $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle successful provisioning
     */
    private function handleProvisioningSuccess(ProvJob $job): void
    {
        Log::info("ProvisioningService: Job {$job->id} completed successfully");

        // Update based on tenant type
        if ($job->tenant_type === 'trial' && $job->tenant_id) {
            $trial = Trial::find($job->tenant_id);
            if ($trial) {
                $trial->update([
                    'status' => Trial::STATUS_DOMAIN_SETUP,
                ]);
                
                $trial->statusHistories()->create([
                    'from_status' => Trial::STATUS_PROVISIONING,
                    'to_status' => Trial::STATUS_DOMAIN_SETUP,
                    'notes' => 'Provisioning completed, domain setup required',
                    'performed_by' => 'system',
                    'performed_by_type' => 'system',
                ]);
                
                Log::info("ProvisioningService: Trial {$trial->id} status updated to domain_setup");
            }
        } elseif ($job->tenant_type === 'subscription' && $job->tenant_id) {
            $subscription = Subscription::find($job->tenant_id);
            if ($subscription) {
                $subscription->update([
                    'status' => 'active',
                ]);
                
                Log::info("ProvisioningService: Subscription {$subscription->id} activated");
            }
        }
    }

    /**
     * Handle failed provisioning
     */
    private function handleProvisioningFailure(ProvJob $job, string $errorMessage): void
    {
        Log::error("ProvisioningService: Job {$job->id} failed", ['error' => $errorMessage]);

        // Check if max attempts reached
        if ($job->attempts >= $job->max_attempts) {
            $job->update(['status' => 'failed']);
            
            if ($job->tenant_type === 'trial' && $job->tenant_id) {
                $trial = Trial::find($job->tenant_id);
                if ($trial) {
                    $trial->update([
                        'status' => Trial::STATUS_FAILED,
                    ]);
                    
                    $trial->statusHistories()->create([
                        'from_status' => Trial::STATUS_PROVISIONING,
                        'to_status' => Trial::STATUS_FAILED,
                        'notes' => "Provisioning failed after {$job->attempts} attempts: {$errorMessage}",
                        'performed_by' => 'system',
                        'performed_by_type' => 'system',
                    ]);
                    
                    Log::error("ProvisioningService: Trial {$trial->id} marked as failed");
                }
            }
        } else {
            // Will be retried
            $job->update(['status' => 'pending']);
        }
    }

    /**
     * Create domain record for trial
     */
    private function createDomainRecord(Trial $trial): Domain
    {
        return Domain::firstOrCreate(
            [
                'domain_name' => $trial->subdomain . '.' . config('app.domain_suffix', 'cooca.id'),
            ],
            [
                'customer_id' => $trial->customer_id,
                'domain_type' => 'subdomain',
                'status' => 'pending',
                'is_primary' => true,
                'ssl_status' => 'pending',
                'dns_status' => 'pending',
                'verified_at' => null,
            ]
        );
    }

    /**
     * Create domain record for subscription
     */
    private function createDomainRecordForSubscription(Subscription $subscription): Domain
    {
        $subdomain = $subscription->subdomain ?? 'tenant-' . Str::random(8);
        
        return Domain::firstOrCreate(
            [
                'domain_name' => $subdomain . '.' . config('app.domain_suffix', 'cooca.id'),
            ],
            [
                'customer_id' => $subscription->customer_id,
                'domain_type' => 'subdomain',
                'status' => 'pending',
                'is_primary' => true,
                'ssl_status' => 'pending',
                'dns_status' => 'pending',
                'verified_at' => null,
            ]
        );
    }

    /**
     * Create ERP request for trial provisioning
     */
    private function createErpRequest(Trial $trial, Domain $domain): \App\Models\ErpRequest
    {
        return \App\Models\ErpRequest::create([
            'request_type' => 'provisioning',
            'entity_type' => 'trial',
            'entity_id' => $trial->id,
            'customer_id' => $trial->customer_id,
            'product_id' => $trial->erp_product_id,
            'status' => 'pending',
            'priority' => 'normal',
            'payload' => [
                'trial_id' => $trial->id,
                'customer_id' => $trial->customer_id,
                'product_id' => $trial->erp_product_id,
                'subdomain' => $trial->subdomain,
                'domain_id' => $domain->id,
                'plan_id' => $trial->subscription_plan_id,
            ],
            'response' => null,
            'processed_at' => null,
        ]);
    }

    /**
     * Create ERP request for subscription provisioning
     */
    private function createErpRequestForSubscription(Subscription $subscription, Domain $domain): \App\Models\ErpRequest
    {
        return \App\Models\ErpRequest::create([
            'request_type' => 'provisioning',
            'entity_type' => 'subscription',
            'entity_id' => $subscription->id,
            'customer_id' => $subscription->customer_id,
            'product_id' => $subscription->subscriptionPlan?->erp_product_id,
            'status' => 'pending',
            'priority' => 'normal',
            'payload' => [
                'subscription_id' => $subscription->id,
                'customer_id' => $subscription->customer_id,
                'plan_id' => $subscription->subscription_plan_id,
                'subdomain' => $subscription->subdomain,
                'domain_id' => $domain->id,
            ],
            'response' => null,
            'processed_at' => null,
        ]);
    }

    /**
     * Mark domain as setup complete
     */
    public function markDomainSetupComplete(string $domainId): void
    {
        $domain = Domain::findOrFail($domainId);
        $domain->update([
            'status' => 'active',
            'dns_status' => 'configured',
            'verified_at' => now(),
        ]);

        Log::info("ProvisioningService: Domain {$domain->domain_name} marked as setup complete");
    }

    /**
     * Mark testing phase complete and start trial
     */
    public function markTestingComplete(Trial $trial): void
    {
        $trial->update([
            'status' => Trial::STATUS_ACTIVE_TRIAL,
            'started_at' => now(),
            'expires_at' => now()->addDays($trial->trial_period_days ?? 14),
        ]);

        $trial->statusHistories()->create([
            'from_status' => Trial::STATUS_TESTING,
            'to_status' => Trial::STATUS_ACTIVE_TRIAL,
            'notes' => 'Testing completed, trial period started',
            'performed_by' => 'system',
            'performed_by_type' => 'system',
        ]);

        Log::info("ProvisioningService: Trial {$trial->id} activated, expires at {$trial->expires_at}");
    }

    /**
     * Get provisioning status for a tenant
     */
    public function getProvisioningStatus(string $tenantType, string $tenantId): array
    {
        $job = ProvJob::where('tenant_type', $tenantType)
            ->where('tenant_id', $tenantId)
            ->orderByDesc('created_at')
            ->first();

        if (!$job) {
            return ['status' => 'not_found', 'message' => 'No provisioning job found'];
        }

        return [
            'job_id' => $job->id,
            'status' => $job->status,
            'current_step' => $job->current_step,
            'attempts' => $job->attempts,
            'max_attempts' => $job->max_attempts,
            'created_at' => $job->created_at,
            'updated_at' => $job->updated_at,
            'metadata' => $job->metadata,
        ];
    }

    /**
     * Health check for provisioned tenant
     */
    public function healthCheck(string $subdomain): bool
    {
        // Check if domain exists and is active
        $domain = Domain::where('domain_name', $subdomain . '.' . config('app.domain_suffix', 'cooca.id'))
            ->where('status', 'active')
            ->first();

        if (!$domain) {
            return false;
        }

        // Check if associated trial or subscription is active
        $trial = Trial::where('subdomain', $subdomain)
            ->where('status', Trial::STATUS_ACTIVE_TRIAL)
            ->first();

        if ($trial) {
            return $trial->expires_at && $trial->expires_at->isFuture();
        }

        $subscription = Subscription::where('subdomain', $subdomain)
            ->whereIn('status', ['active', 'trial'])
            ->first();

        return $subscription !== null;
    }
}
