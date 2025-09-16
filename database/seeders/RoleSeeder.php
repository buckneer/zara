<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create admin role if it doesn't exist
        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            ['label' => 'Administrator']
        );

        // Find user with id 2
        $user = User::find(2);

        if ($user) {
            // Attach role without creating duplicates
            $user->roles()->syncWithoutDetaching([$admin->id]);

            // Console feedback when running seeder
            $this->command->info("Assigned 'admin' role (id: {$admin->id}) to user id 2.");
        } else {
            $this->command->warn("User with id 2 not found. Role created but not attached.");
        }
    }
}
