<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@arameglobal.com',
                'password' => Hash::make('admin@arameglobal.com'),
                'role_id' => 1, // Admin role
            ],
            [
                'name' => 'Hari',
                'email' => 'hari@arameglobal.com',
                'password' => Hash::make('hari@arameglobal.com'),
                'role_id' => 3, // Employee role
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}