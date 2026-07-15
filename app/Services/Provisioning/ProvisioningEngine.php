<?php

declare(strict_types=1);

namespace App\Services\Provisioning;

use App\Models\ProvisioningJob as ProvJob;
use App\Models\ErpRequest;
use App\Models\Domain;
use App\Models\License;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Carbon\Carbon;

final class ProvisioningEngine
{
    /**
     * Entry point to run a provisioning job.
     * Implements idempotency: resumes from current_step.
     */
    public function run(ProvJob $job): void
    {
        Log::info("ProvisioningEngine: Starting job {$job->id} at step {$job->current_step}");

        $job->update(['status' => 'running', 'attempts' => $job->attempts + 1]);
        $erpRequest = clone $job->erpRequest; // Keep a reference

        try {
            if ($job->current_step === 'init') {
                $this->stepInit($job);
            }
            if ($job->current_step === 'create_db') {
                $this->stepCreateDb($job);
            }
            if ($job->current_step === 'migrate') {
                $this->stepMigrate($job);
            }
            if ($job->current_step === 'seed') {
                $this->stepSeed($job);
            }
            if ($job->current_step === 'generate_license') {
                $this->stepGenerateLicense($job);
            }
            if ($job->current_step === 'set_domain') {
                $this->stepSetDomain($job);
            }
            if ($job->current_step === 'verify') {
                $this->stepVerify($job);
            }
            
            // If all steps succeed
            $this->completeJob($job, $erpRequest);

        } catch (\Exception $e) {
            Log::error("ProvisioningEngine: Error at step {$job->current_step}", [
                'job_id' => $job->id,
                'error' => $e->getMessage()
            ]);

            $status = $job->attempts >= 3 ? 'failed' : 'step_failed';

            $job->update([
                'status' => $status,
                'error_message' => $e->getMessage(),
            ]);

            // If failed after 3 retries, mark ErpRequest as rejected
            if ($status === 'failed' && $job->erpRequest) {
                $job->erpRequest->update([
                    'status' => ErpRequest::STATUS_REJECTED,
                    'notes' => 'Provisioning failed permanently. Error: ' . $e->getMessage(),
                ]);
            }
            
            throw $e;
        }
    }

    private function stepInit(ProvJob $job): void
    {
        Log::info("ProvisioningEngine: Init step for job {$job->id}");
        if ($job->erpRequest) {
            $job->erpRequest->markInSetup();
        }
        $job->update(['current_step' => 'create_db']);
    }

    private function stepCreateDb(ProvJob $job): void
    {
        Log::info("ProvisioningEngine: Creating DB {$job->db_name}");
        // In shared hosting, this requires GRANT statements via root.
        // Assuming the laravel app connects as root or a user with CREATE DATABASE privileges.
        $dbName = $job->db_name;
        $dbUser = $job->db_user;
        $dbPass = $job->db_password;

        // Ensure safe names
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $dbName) || !preg_match('/^[a-zA-Z0-9_]+$/', $dbUser)) {
            throw new \Exception("Invalid database or user name format");
        }

        DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Wait, on some shared hostings CREATE USER might fail if it exists, so we ignore errors or check.
        try {
            DB::statement("CREATE USER IF NOT EXISTS '{$dbUser}'@'%' IDENTIFIED BY '{$dbPass}'");
        } catch (\Exception $e) {
            // Ignore if user already exists
        }
        
        DB::statement("GRANT ALL PRIVILEGES ON `{$dbName}`.* TO '{$dbUser}'@'%'");
        DB::statement("FLUSH PRIVILEGES");

        $job->update(['current_step' => 'migrate']);
    }

    private function stepMigrate(ProvJob $job): void
    {
        Log::info("ProvisioningEngine: Migrating DB {$job->db_name}");
        
        $this->configureTenantConnection($job);

        // Run migrations on tenant database
        // Assuming tenant migrations are stored in database/migrations/tenant
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        $job->update(['current_step' => 'seed']);
    }

    private function stepSeed(ProvJob $job): void
    {
        Log::info("ProvisioningEngine: Seeding DB {$job->db_name}");
        
        $this->configureTenantConnection($job);

        // Assuming there is a Database\Seeders\TenantDatabaseSeeder
        if (class_exists(\Database\Seeders\TenantDatabaseSeeder::class)) {
            Artisan::call('db:seed', [
                '--database' => 'tenant',
                '--class' => 'TenantDatabaseSeeder',
                '--force' => true,
            ]);
        }

        $job->update(['current_step' => 'generate_license']);
    }

    private function stepGenerateLicense(ProvJob $job): void
    {
        Log::info("ProvisioningEngine: Generating license for job {$job->id}");
        
        if ($job->erpRequest) {
            // Generate a secure license key
            $key = 'COOCA-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
            
            License::create([
                'erp_request_id' => $job->erp_request_id,
                'license_key' => $key,
                'status' => 'active',
                'valid_until' => $job->erpRequest->trial_ends_at ?? Carbon::now()->addDays(14), // Default 14 days trial
                'domain_limit' => 1,
            ]);
        }

        $job->update(['current_step' => 'set_domain']);
    }

    private function stepSetDomain(ProvJob $job): void
    {
        Log::info("ProvisioningEngine: Setting up domain {$job->subdomain}");
        
        if ($job->erpRequest) {
            Domain::updateOrCreate(
                [
                    'erp_request_id' => $job->erp_request_id,
                    'domain_name' => $job->subdomain . '.cooca.id',
                ],
                [
                    'type' => 'subdomain',
                    'status' => 'active',
                    'verified_at' => now(),
                ]
            );
            $job->erpRequest->markDomainSetup();
        }

        $job->update(['current_step' => 'verify']);
    }

    private function stepVerify(ProvJob $job): void
    {
        Log::info("ProvisioningEngine: Verifying deployment for job {$job->id}");
        // Here we could hit the tenant's /api/health endpoint
        // Http::get("https://{$job->subdomain}.cooca.id/api/health");
        
        if ($job->erpRequest) {
            $job->erpRequest->markTesting();
        }

        $job->update(['current_step' => 'complete']);
    }

    private function completeJob(ProvJob $job, ?ErpRequest $erpRequest): void
    {
        Log::info("ProvisioningEngine: Job {$job->id} completed successfully");
        $job->update([
            'status' => 'completed',
        ]);

        if ($erpRequest) {
            // Define trial period
            $startsAt = now();
            $endsAt = now()->addDays(14); // Fetch from product plan if needed
            
            $erpRequest->activateTrial($startsAt, $endsAt);
            
            // Trigger Notification to customer that ERP is ready
            // Notification::send(...)
        }
    }

    /**
     * Rollback a failed provisioning job
     */
    public function rollback(ProvJob $job): void
    {
        Log::warning("ProvisioningEngine: Rolling back job {$job->id}");
        
        // 1. Drop database
        try {
            DB::statement("DROP DATABASE IF EXISTS `{$job->db_name}`");
            DB::statement("DROP USER IF EXISTS '{$job->db_user}'@'%'");
            Log::info("ProvisioningEngine: Rolled back DB {$job->db_name}");
        } catch (\Exception $e) {
            Log::error("ProvisioningEngine: Failed to drop DB {$job->db_name}", ['error' => $e->getMessage()]);
        }

        // 2. Remove Subdomain / Domain records
        Domain::where('erp_request_id', $job->erp_request_id)->delete();
        
        // 3. Remove License
        License::where('erp_request_id', $job->erp_request_id)->delete();

        // 4. Update status
        $job->update(['status' => 'rolled_back']);
        if ($job->erpRequest) {
            $job->erpRequest->update([
                'status' => ErpRequest::STATUS_REJECTED,
                'notes' => 'Rolled back after failure.'
            ]);
        }
    }

    /**
     * Configure Laravel database connection for the tenant dynamically
     */
    private function configureTenantConnection(ProvJob $job): void
    {
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $job->db_name,
            'username' => $job->db_user,
            'password' => $job->db_password,
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                \PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ]);

        DB::purge('tenant');
    }
}
