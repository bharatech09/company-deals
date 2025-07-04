<?php

namespace Database\Seeders;
use App\Models\Admin;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminData = [
            'name' => 'Super Admin',
            'email' => 'admin@mail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password')

        ];
        Admin::create($adminData);
    }
}
