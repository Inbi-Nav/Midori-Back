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
        User::create([
            'name' => 'Admin Midori',
            'email' => 'admin@midori.com',
            'role' => 'admin',
            'password' => Hash::make('midori2026'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Sara Garcia',
            'email' => 'Sara@gmail.com',
            'role' => 'client',
            'password' => Hash::make('Sara1234'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Inbi',
            'email' => 'inbi@midori.com',
            'role' => 'provider',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
    }
}