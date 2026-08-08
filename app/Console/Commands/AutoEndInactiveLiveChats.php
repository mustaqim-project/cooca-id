<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\LiveChat\LiveChatService;
use Illuminate\Console\Command;

final class AutoEndInactiveLiveChats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live-chats:auto-end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically end live chat sessions that have been inactive for 2 minutes or more.';

    /**
     * Execute the console command.
     */
    public function handle(LiveChatService $liveChatService): int
    {
        $endedCount = $liveChatService->autoEndInactiveChats();
        $this->info("Auto-ended {$endedCount} inactive live chat session(s).");

        return Command::SUCCESS;
    }
}
