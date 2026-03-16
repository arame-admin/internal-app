<?php

namespace App\Console\Commands;

use App\Models\Timesheet;
use App\Models\TimesheetReminder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckMissedTimesheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timesheet:check-missed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for missed timesheets from previous days and create reminders';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for missed timesheets...');

        // Get yesterday's date
        $yesterday = Carbon::yesterday()->toDateString();
        
        // Get all active users
        $users = User::where('is_active', true)->get();
        
        $reminderCount = 0;

        foreach ($users as $user) {
            // Check if user has a timesheet for yesterday
            $hasTimesheet = Timesheet::where('user_id', $user->id)
                ->where('date', $yesterday)
                ->exists();

            // If no timesheet exists for yesterday, create a reminder
            if (!$hasTimesheet) {
                // Check if reminder already exists
                $existingReminder = TimesheetReminder::where('user_id', $user->id)
                    ->where('missed_date', $yesterday)
                    ->exists();

                if (!$existingReminder) {
                    TimesheetReminder::create([
                        'user_id' => $user->id,
                        'missed_date' => $yesterday,
                        'status' => TimesheetReminder::STATUS_ACTIVE,
                    ]);
                    
                    $reminderCount++;
                    $this->line("Created reminder for {$user->name} for date {$yesterday}");
                }
            }
        }

        $this->info("Created {$reminderCount} new reminder(s) for missed timesheets.");
        
        return Command::SUCCESS;
    }
}
