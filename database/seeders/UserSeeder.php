<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin Inventaris', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'role' => 'user'],
            ['name' => 'Siti Aminah', 'email' => 'siti@example.com', 'role' => 'user'],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->forceFill([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ])->save();
        }
    }
}