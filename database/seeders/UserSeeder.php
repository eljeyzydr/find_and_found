<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin default
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@findandfound.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // User demo
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891',
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}