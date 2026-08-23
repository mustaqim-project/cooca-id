<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Ai\AiTokenExpirationService;
use Illuminate\Console\Command;

class ExpireAiTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:expire-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan and expire AI token lots that have exceeded their 30-day validity period';

    /**
     * Execute the console command.
     */
    public function handle(AiTokenExpirationService $expirationService): int
    {
        $this->info('Scanning and processing expired AI token lots...');

        $result = $expirationService->processExpirations();

        $this->info("Completed! Processed lots: {$result['processed_lots']}, Expired tokens: {$result['expired_tokens']}");

        return Command::SUCCESS;
    }
}
