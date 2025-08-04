<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Master',
            'username' => 'admin01',
            'email' => 'admin@example.com',
            'telephone' => '08123456789',
            'department' => 'Admin Center',
            'role' => 'admin',
            'pss_code' => 'ADM001',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'User Pertamina',
            'username' => 'pertamina01',
            'email' => 'pertamina@example.com',
            'telephone' => '08129876543',
            'department' => 'Pertamina Unit A',
            'role' => 'PERTAMINA',
            'pss_code' => 'PTM001',
            'password' => Hash::make('password123'),
        ]);
    }
}