<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Products
            ['name' => 'Access Products', 'slug' => 'access-products', 'module' => 'products'],
            ['name' => 'Manage Products', 'slug' => 'manage-products', 'module' => 'products'],
            
            // Customers
            ['name' => 'Access Customers', 'slug' => 'access-customers', 'module' => 'customers'],
            ['name' => 'Manage Customers', 'slug' => 'manage-customers', 'module' => 'customers'],
            
            // Billing
            ['name' => 'Access Billing', 'slug' => 'access-billing', 'module' => 'billing'],
            ['name' => 'Create Bills', 'slug' => 'create-bills', 'module' => 'billing'],
            
            // Reports
            ['name' => 'Access Reports', 'slug' => 'access-reports', 'module' => 'reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign permissions to roles
        $superAdmin = Role::where('slug', 'super-admin')->first();
        $stockManager = Role::where('slug', 'stock-manager')->first();
        $salesMan = Role::where('slug', 'sales-man')->first();

        // Super Admin gets all permissions
        if ($superAdmin) {
            $superAdmin->permissions()->attach(Permission::pluck('id'));
        }

        // Stock Manager permissions
        if ($stockManager) {
            $stockManager->permissions()->attach(
                Permission::whereIn('slug', [
                    'access-products',
                    'manage-products',
                    'access-customers',
                    'access-reports',
                ])->pluck('id')
            );
        }

        // Sales Man permissions
        if ($salesMan) {
            $salesMan->permissions()->attach(
                Permission::whereIn('slug', [
                    'access-customers',
                    'access-billing',
                    'create-bills',
                ])->pluck('id')
            );
        }
    }
}
