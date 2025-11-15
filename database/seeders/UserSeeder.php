<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Super Admin role
        $superAdminRole = Role::where('slug', 'super-admin')->first();

        if (!$superAdminRole) {
            $this->command->error('Super Admin role not found. Please run RoleSeeder first.');
            return;
        }

        // Create single Super Admin user
        User::updateOrCreate(
            ['email' => 'admin@billing.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@billing.com',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
            ]
        );

        $this->command->info('Super Admin user created successfully!');
        $this->command->info('Email: admin@billing.com');
        $this->command->info('Password: password');
    }
}
