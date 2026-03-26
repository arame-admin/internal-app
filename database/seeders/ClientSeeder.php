<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1 555-0101',
                'address' => '123 Main Street, New York, NY 10001',
                'status' => 'active',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+1 555-0102',
                'address' => '456 Oak Avenue, Los Angeles, CA 90001',
                'status' => 'active',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'phone' => '+1 555-0103',
                'address' => '789 Pine Road, Chicago, IL 60601',
                'status' => 'active',
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice.brown@example.com',
                'phone' => '+1 555-0104',
                'address' => '321 Elm Street, Houston, TX 77001',
                'status' => 'active',
            ],
            [
                'name' => 'Charlie Wilson',
                'email' => 'charlie.wilson@example.com',
                'phone' => '+1 555-0105',
                'address' => '654 Maple Drive, Phoenix, AZ 85001',
                'status' => 'active',
            ],
            [
                'name' => 'Diana Davis',
                'email' => 'diana.davis@example.com',
                'phone' => '+1 555-0106',
                'address' => '987 Cedar Lane, Philadelphia, PA 19101',
                'status' => 'active',
            ],
            [
                'name' => 'Edward Miller',
                'email' => 'edward.miller@example.com',
                'phone' => '+1 555-0107',
                'address' => '147 Birch Court, San Antonio, TX 78201',
                'status' => 'active',
            ],
            [
                'name' => 'Fiona Garcia',
                'email' => 'fiona.garcia@example.com',
                'phone' => '+1 555-0108',
                'address' => '258 Walnut Way, San Diego, CA 92101',
                'status' => 'active',
            ],
        ];

        foreach ($clients as $client) {
            $created = Client::firstOrCreate(
                ['email' => $client['email']],
                $client
            );
            
            // Create contact person for each client
            \App\Models\ContactPerson::firstOrCreate(
                ['client_id' => $created->id, 'name' => $client['name']],
                [
                    'client_id' => $created->id,
                    'name' => $client['name'],
                    'email' => $client['email'],
                    'phone' => $client['phone'],
                ]
            );
        }
    }
}
