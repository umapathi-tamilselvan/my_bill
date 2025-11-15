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

        // Create Super Admin User
        $superAdminRole = \App\Models\Role::where('slug', 'super-admin')->first();
        
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@billing.com',
            'password' => bcrypt('password'),
            'role_id' => $superAdminRole->id,
        ]);

        // Create Stock Manager User
        $stockManagerRole = \App\Models\Role::where('slug', 'stock-manager')->first();
        
        User::create([
            'name' => 'Stock Manager',
            'email' => 'stock@billing.com',
            'password' => bcrypt('password'),
            'role_id' => $stockManagerRole->id,
        ]);

        // Create Sales Man User
        $salesManRole = \App\Models\Role::where('slug', 'sales-man')->first();
        
        User::create([
            'name' => 'Sales Man',
            'email' => 'sales@billing.com',
            'password' => bcrypt('password'),
            'role_id' => $salesManRole->id,
        ]);
    }
}
