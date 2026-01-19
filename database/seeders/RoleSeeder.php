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
                'name' => 'Admin',
                'description' => 'Full system access with all permissions',
                'status' => true
            ],
            [
                'name' => 'Manager',
                'description' => 'Team management and reporting access',
                'status' => true
            ],
            [
                'name' => 'Employee',
                'description' => 'Standard employee access',
                'status' => true
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}