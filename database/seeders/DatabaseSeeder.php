<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Timesheet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(MasterDataSeeder::class);
        $this->call(UserSeeder::class);

        // Sample Timesheets
        $users = User::inRandomOrder()->limit(3)->get();
        $approvers = User::where('role_id', '!=', 1)->inRandomOrder()->limit(2)->get(); // managers
        
        foreach ($users as $user) {
            Timesheet::create([
                'user_id' => $user->id,
                'date' => now()->subDays(3),
                'hours' => 8.0,
                'description' => 'Daily development work',
                'status' => 'approved',
                'approved_by' => $approvers->random()->id ?? null,
            ]);
            
            Timesheet::create([
                'user_id' => $user->id,
                'date' => now()->subDays(1),
                'hours' => 4.5,
                'description' => 'Client meeting and code review',
                'status' => 'pending',
            ]);
            
            Timesheet::create([
                'user_id' => $user->id,
                'date' => now()->subWeek(),
                'hours' => 7.5,
                'description' => 'Feature development',
                'status' => 'draft',
            ]);
        }

        // User::factory(10)->create();
    }
}
