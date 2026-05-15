<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Bookify',
            'email' => 'admin@bookify.com',
            'password' => Hash::make('password123'),
            'business_type' => 'micro',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Toko Online',
            'email' => 'toko@bookify.com',
            'password' => Hash::make('password123'),
            'business_type' => 'online',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Freelancer Profesional',
            'email' => 'freelancer@bookify.com',
            'password' => Hash::make('password123'),
            'business_type' => 'freelancer',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Toko Kelontong',
            'email' => 'toko.kelontong@bookify.com',
            'password' => Hash::make('password123'),
            'business_type' => 'micro',
            'email_verified_at' => now(),
        ]);
    }
}
