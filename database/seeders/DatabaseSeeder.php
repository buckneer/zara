<?php

namespace Database\Seeders;

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
        // Roles must exist first so UserSeeder can assign them
        $this->call([
            RoleSeeder::class,       // create 'admin' and 'user' roles
            UserSeeder::class,       // create admin + normal user and attach roles
            CategorySeeder::class,   // fashion categories
            ProductSeeder::class,    // products + images + attach categories
        ]);
    }
}
