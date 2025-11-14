<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'role' => User::ROLE_CASHIER,
                'name' => env('CASHIER_NAME', 'Main Cashier'),
                'email' => env('CASHIER_EMAIL', 'cashier@blessedcafe.test'),
                'password' => env('CASHIER_PASSWORD', 'password'),
            ],
            [
                'role' => User::ROLE_KITCHEN,
                'name' => env('KITCHEN_NAME', 'Kitchen Display'),
                'email' => env('KITCHEN_EMAIL', 'kitchen@blessedcafe.test'),
                'password' => env('KITCHEN_PASSWORD', 'password'),
            ],
            [
                'role' => User::ROLE_INVENTORY,
                'name' => env('INVENTORY_NAME', 'Inventory Controller'),
                'email' => env('INVENTORY_EMAIL', 'inventory@blessedcafe.test'),
                'password' => env('INVENTORY_PASSWORD', 'password'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'role' => $user['role'],
                ]
            );
        }
    }
}
