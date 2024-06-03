<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'address' => 'Jl. Admin',
                'phone' => '081234567890',
                'password' => Hash::make('admin'),
                'roles' => 'Admin',
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'address' => 'Jl. User',
                'phone' => '081234567891',
                'password' => Hash::make('user'),
                'roles' => 'User',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Generate additional 18 users using the factory
        User::factory()->count(18)->create();
    }
}
