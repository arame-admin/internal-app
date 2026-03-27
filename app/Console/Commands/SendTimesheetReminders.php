<?php

namespace App\Console\Commands;

use App\Mail\TimesheetReminderNotification;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTimesheetReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timesheet:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to users who have not submitted their timesheet for yesterday';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for missed timesheets...');

        // Get yesterday's date
        $yesterday = Carbon::yesterday();

        // Get all active users (excluding admin role)
        $users = User::where('is_active', true)
            ->whereHas('role', function ($query) {
                $query->where('name', '!=', 'Admin');
            })
            ->get();

        $sentCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            // Check if user has a timesheet for yesterday
            $hasTimesheet = Timesheet::where('user_id', $user->id)
                ->where('date', $yesterday->toDateString())
                ->exists();

            // If no timesheet exists for yesterday, send a reminder
            if (!$hasTimesheet) {
                try {
                    Mail::to($user->email)->send(new TimesheetReminderNotification($user, $yesterday));
                    $sentCount++;
                    $this->line("Sent timesheet reminder to {$user->name} ({$user->email})");
                } catch (\Exception $e) {
                    $failedCount++;
                    $this->error("Failed to send timesheet reminder to {$user->name}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Timesheet reminder sending completed. Sent: {$sentCount}, Failed: {$failedCount}");

        return Command::SUCCESS;
    }
}
