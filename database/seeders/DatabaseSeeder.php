<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Roles
        $this->call(RoleSeeder::class);
        
        // Seed Permissions
        $this->call(PermissionSeeder::class);

        // Seed User (Single Super Admin)
        $this->call(UserSeeder::class);
    }
}
