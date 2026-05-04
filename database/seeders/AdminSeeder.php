<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Boss',
            'email' => 'admin@bosgroup.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Promoter One',
            'email' => 'promoter@example.com',
            'password' => Hash::make('password123'),
            'role' => 'promoter',
        ]);
    }
}
