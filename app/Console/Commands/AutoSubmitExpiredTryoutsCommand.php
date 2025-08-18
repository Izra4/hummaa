<?php 

namespace App\Console\Commands;

use App\Jobs\AutoSubmitExpiredTryouts;
use Illuminate\Console\Command;

class AutoSubmitExpiredTryoutsCommand extends Command
{
    protected $signature = 'tryouts:auto-submit-expired';
    protected $description = 'Auto-submit expired tryout sessions';

    public function handle(): int
    {
        $this->info('Starting auto-submit of expired tryout sessions...');
        
        AutoSubmitExpiredTryouts::dispatch();
        
        $this->info('Auto-submit job dispatched successfully.');
        
        return Command::SUCCESS;
    }
}
