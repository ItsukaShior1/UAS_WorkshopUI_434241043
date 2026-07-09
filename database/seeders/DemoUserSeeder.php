<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $demos = [
            [
                'name' => 'User Demo Bookify',
                'email' => 'user.demo@bookify.com',
                'password' => Hash::make('password123'),
                'business_type' => 'micro',
                'role' => User::ROLE_USER,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'User Expired',
                'email' => 'user.expired@bookify.com',
                'password' => Hash::make('password123'),
                'business_type' => 'micro',
                'role' => User::ROLE_USER,
                'is_active' => false,
                'deactivated_reason' => 'Langganan berakhir',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($demos as $data) {
            User::updateOrCreate(['email' => $data['email']], $data);
        }
    }
}
