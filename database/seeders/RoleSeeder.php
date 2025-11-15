<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full system access with all permissions',
                'is_active' => true,
            ],
            [
                'name' => 'Stock Manager',
                'slug' => 'stock-manager',
                'description' => 'Can manage products and stock',
                'is_active' => true,
            ],
            [
                'name' => 'Sales Man',
                'slug' => 'sales-man',
                'description' => 'Can create bills and manage sales',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
