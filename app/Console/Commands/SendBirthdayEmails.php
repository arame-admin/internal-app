<?php

namespace App\Console\Commands;

use App\Mail\BirthdayNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBirthdayEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday greeting emails to users whose birthday is today';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for birthdays today...');

        $today = Carbon::today();
        $todayMonth = $today->month;
        $todayDay = $today->day;

        // Get all active users whose birthday is today
        $users = User::where('is_active', true)
            ->whereNotNull('dob')
            ->get()
            ->filter(function ($user) use ($todayMonth, $todayDay) {
                return $user->dob->month === $todayMonth && $user->dob->day === $todayDay;
            });

        if ($users->isEmpty()) {
            $this->info('No birthdays found for today.');
            return Command::SUCCESS;
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new BirthdayNotification($user));
                $sentCount++;
                $this->line("Sent birthday email to {$user->name} ({$user->email})");
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("Failed to send birthday email to {$user->name}: {$e->getMessage()}");
            }
        }

        $this->info("Birthday email sending completed. Sent: {$sentCount}, Failed: {$failedCount}");

        return Command::SUCCESS;
    }
}
