<?php 

namespace App\Console\Commands;

use App\Models\TryoutResult;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearOldTryoutSessions extends Command
{
    protected $signature = 'tryouts:clear-old-sessions {--days=30 : Number of days to keep}';
    protected $description = 'Clear old completed tryout sessions';

    public function handle(): int
    {
        $days = $this->option('days');
        
        $this->info("Clearing tryout sessions older than {$days} days...");
        
        $deletedCount = TryoutResult::where('status', 'selesai')
            ->where('waktu_selesai', '<', now()->subDays($days))
            ->count();
            
        if ($deletedCount > 0) {
            $confirm = $this->confirm("This will delete {$deletedCount} old tryout sessions. Continue?");
            
            if ($confirm) {
                TryoutResult::where('status', 'selesai')
                    ->where('waktu_selesai', '<', now()->subDays($days))
                    ->delete();
                    
                $this->info("Deleted {$deletedCount} old tryout sessions.");
            } else {
                $this->info('Operation cancelled.');
            }
        } else {
            $this->info('No old sessions found to delete.');
        }
        
        return Command::SUCCESS;
    }
}