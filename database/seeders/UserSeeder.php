<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Designation;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get designation IDs by code
        $financeExecutiveId = Designation::where('code', 'FIN')->value('id');
        $financeManagerId = Designation::where('code', 'FINM')->value('id');
        $managerId = Designation::where('code', 'MGR')->value('id');
        $ceoId = Designation::where('code', 'CEO')->value('id');

        $users = [
            [
                'name' => 'Admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@arameglobal.com',
                'personal_email' => 'admin@example.com',
                'password' => Hash::make('admin@arameglobal.com'),
                'employee_code' => '1000',
                'role_id' => 1, // Admin role
                'department_id' => 2, // Engineering
                'designation_id' => null,
                'bu_id' => 1,
                'location_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Susan Jacob',
                'first_name' => 'Susan',
                'last_name' => 'Jacob',
                'email' => 'susanjacob@arameglobal.com',
                'personal_email' => 'susan@example.com',
                'password' => Hash::make('susanjacob@arameglobal.com'),
                'employee_code' => '1001',
                'role_id' => 2, // Manager role
                'department_id' => 1, // Leadership
                'designation_id' => $ceoId,
                'bu_id' => 1,
                'location_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Hari Krishnan',
                'first_name' => 'Hari',
                'last_name' => 'Krishnan',
                'email' => 'harikrishnan@arameglobal.com',
                'personal_email' => 'hari@example.com',
                'password' => Hash::make('harikrishnan@arameglobal.com'),
                'employee_code' => '1002',
                'role_id' => 2, // Manager role
                'department_id' => 2, // Engineering
                'designation_id' => $managerId,
                'bu_id' => 1,
                'location_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Roshni',
                'first_name' => 'Roshni',
                'last_name' => '',
                'email' => 'roshni@arameglobal.com',
                'personal_email' => 'roshni@example.com',
                'password' => Hash::make('roshni@arameglobal.com'),
                'employee_code' => '1003',
                'role_id' => 3, // Employee role
                'department_id' => 6, // Finance
                'designation_id' => $financeExecutiveId,
                'bu_id' => 1,
                'location_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Sheril',
                'first_name' => 'Sheril',
                'last_name' => '',
                'email' => 'sheril@arameglobal.com',
                'personal_email' => 'sheril@example.com',
                'password' => Hash::make('sheril@arameglobal.com'),
                'employee_code' => '1004',
                'role_id' => 3, // Employee role
                'department_id' => 6, // Finance
                'designation_id' => $financeManagerId,
                'bu_id' => 1,
                'location_id' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
