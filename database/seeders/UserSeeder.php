<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Normal user (plain password because your model casts 'password' => 'hashed')
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Normal User',
                'password' => 'secret123',
            ]
        );

        $userRole = Role::firstWhere('name', 'user');
        if ($userRole) {
            $user->roles()->syncWithoutDetaching([$userRole->id]);
        }

        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'admin1234',
            ]
        );

        $adminRole = Role::firstWhere('name', 'admin');
        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }
    }
}
