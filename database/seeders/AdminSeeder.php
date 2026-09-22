<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin One',
            'email' => 'admin1@library.com',
            'password' => Hash::make('Admin1234'),
        ]);

        Admin::create([
            'name' => 'Admin Two',
            'email' => 'admin2@library.com',
            'password' => Hash::make('Admin1234'),
        ]);
    }
}