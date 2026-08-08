<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Domain;
use App\Mail\Admin\HealthCheckFailedMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

final class MonitorTenantsHealthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:monitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor active tenant subdomains via HTTP GET health check';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("Starting tenant health monitoring...");

        $activeDomains = Domain::where('status', Domain::STATUS_ACTIVE)
            ->with('customer')
            ->get();

        if ($activeDomains->isEmpty()) {
            $this->info("No active domains found to monitor.");
            return Command::SUCCESS;
        }

        foreach ($activeDomains as $domain) {
            $url = "https://{$domain->domain}/api/health";
            $this->line("Checking health for: {$domain->domain} ({$url})");

            try {
                // Short timeout of 5 seconds
                $response = Http::timeout(5)->get($url);

                if ($response->successful()) {
                    $this->info("  -> {$domain->domain} is healthy.");
                } else {
                    $this->warn("  -> {$domain->domain} returned status " . $response->status());
                    $this->triggerAlert($domain, "Returned status code " . $response->status());
                }
            } catch (\Exception $e) {
                $this->error("  -> {$domain->domain} check failed: " . $e->getMessage());
                $this->triggerAlert($domain, $e->getMessage());
            }
        }

        $this->info("Tenant health monitoring finished.");
        return Command::SUCCESS;
    }

    /**
     * Trigger alert and send email to admin.
     */
    private function triggerAlert(Domain $domain, string $errorDetails): void
    {
        $customer = $domain->customer;
        if (!$customer) {
            Log::warning("Cannot send alert for domain {$domain->domain}: customer not associated.");
            return;
        }

        // Send to system admin email
        $adminEmail = config('mail.from.address', 'admin@cooca.id');

        try {
            Mail::to($adminEmail)->send(new HealthCheckFailedMail(
                $customer,
                $domain->domain,
                $errorDetails,
                now()->toDateTimeString(),
                route('admin.dashboard')
            ));
            
            Log::info("Sent HealthCheckFailedMail for domain {$domain->domain} to {$adminEmail}");
        } catch (\Exception $e) {
            Log::error("Failed sending HealthCheckFailedMail for domain {$domain->domain}: " . $e->getMessage());
        }
    }
}
