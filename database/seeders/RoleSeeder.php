<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Admin',
                'description' => 'Full system access with all permissions',
                'is_active' => true
            ],
            [
                'id' => 2,
                'name' => 'Leadership',
                'description' => 'Strategic leadership and company-wide access',
                'is_active' => true
            ],
            [
                'id' => 3,
                'name' => 'Managers',
                'description' => 'Team management and reporting access',
                'is_active' => true
            ],
            [
                'id' => 4,
                'name' => 'Asst Managers',
                'description' => 'Assistant management and team support',
                'is_active' => true
            ],
            [
                'id' => 5,
                'name' => 'Team Lead',
                'description' => 'Team coordination and leadership',
                'is_active' => true
            ],
            [
                'id' => 6,
                'name' => 'Users',
                'description' => 'Standard employee access',
                'is_active' => true
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['id' => $role['id']], $role);
        }
    }
}
