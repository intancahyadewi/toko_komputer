<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin
        User::updateOrCreate(
            ['email' => 'admin@tokokomputer.test'],
            [
                'name' => 'Admin 1',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Membuat akun User
        User::updateOrCreate(
            ['email' => 'user1@toko.test'],
            [
                'name' => 'intancahya',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ]
        );
    }
}
