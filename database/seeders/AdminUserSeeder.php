<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'phone' => '1234567890',
            'username' => 'admin',
            'password' => Hash::make('1234'),
            'role' => 'Admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
