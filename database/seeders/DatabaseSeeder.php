<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        User::updateOrCreate(
            ['email' => 'admin@tokokomputer.test'],
            [
                'name' => 'Admin 1',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user1@toko.test'],
            [
                'name' => 'intancahya',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ]
        );
        User::updateOrCreate(
            ['email' => 'user2@toko.test'],
            [
                'name' => 'aszahraberlian',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user3@toko.test'],
            [
                'name' => 'shorichatulmalicha',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user4@toko.test'],
            [
                'name' => 'dira',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user5@toko.test'],
            [
                'name' => 'hanapaulina',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user6@toko.test'],
            [
                'name' => 'nasywapraja',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ]
        );
    }
}
