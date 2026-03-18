<?php

namespace App\Console\Commands;

use App\Models\TimesheetReminder;
use Illuminate\Console\Command;

class CleanupTimesheetReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timesheet:cleanup-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove timesheet reminders that are older than 48 hours';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Cleaning up expired timesheet reminders...');

        // Delete reminders older than 48 hours
        $deletedCount = TimesheetReminder::where('created_at', '<', now()->subHours(48))
            ->delete();

        $this->info("Removed {$deletedCount} expired reminder(s).");
        
        return Command::SUCCESS;
    }
}
