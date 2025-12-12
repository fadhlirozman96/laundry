<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            // System Management (Super Admin only)
            ['name' => 'view-system-settings', 'display_name' => 'View System Settings', 'group' => 'system'],
            ['name' => 'manage-system-settings', 'display_name' => 'Manage System Settings', 'group' => 'system'],
            ['name' => 'view-all-business-owners', 'display_name' => 'View All Business Owners', 'group' => 'system'],
            ['name' => 'view-all-stores', 'display_name' => 'View All Stores', 'group' => 'system'],
            ['name' => 'view-all-users', 'display_name' => 'View All Users', 'group' => 'system'],
            ['name' => 'access-any-data', 'display_name' => 'Access Any Data', 'group' => 'system'],

            // Store Management
            ['name' => 'view-stores', 'display_name' => 'View Stores', 'group' => 'stores'],
            ['name' => 'create-store', 'display_name' => 'Create Store', 'group' => 'stores'],
            ['name' => 'edit-store', 'display_name' => 'Edit Store', 'group' => 'stores'],
            ['name' => 'delete-store', 'display_name' => 'Delete Store', 'group' => 'stores'],
            ['name' => 'view-store-details', 'display_name' => 'View Store Details', 'group' => 'stores'],

            // User Management
            ['name' => 'view-users', 'display_name' => 'View Users', 'group' => 'users'],
            ['name' => 'create-user', 'display_name' => 'Create User', 'group' => 'users'],
            ['name' => 'edit-user', 'display_name' => 'Edit User', 'group' => 'users'],
            ['name' => 'delete-user', 'display_name' => 'Delete User', 'group' => 'users'],
            ['name' => 'assign-user-to-store', 'display_name' => 'Assign User to Store', 'group' => 'users'],

            // Product Management
            ['name' => 'view-products', 'display_name' => 'View Products', 'group' => 'products'],
            ['name' => 'create-product', 'display_name' => 'Create Product', 'group' => 'products'],
            ['name' => 'edit-product', 'display_name' => 'Edit Product', 'group' => 'products'],
            ['name' => 'delete-product', 'display_name' => 'Delete Product', 'group' => 'products'],
            ['name' => 'view-product-details', 'display_name' => 'View Product Details', 'group' => 'products'],
            ['name' => 'import-products', 'display_name' => 'Import Products', 'group' => 'products'],
            ['name' => 'export-products', 'display_name' => 'Export Products', 'group' => 'products'],

            // Category Management
            ['name' => 'view-categories', 'display_name' => 'View Categories', 'group' => 'categories'],
            ['name' => 'create-category', 'display_name' => 'Create Category', 'group' => 'categories'],
            ['name' => 'edit-category', 'display_name' => 'Edit Category', 'group' => 'categories'],
            ['name' => 'delete-category', 'display_name' => 'Delete Category', 'group' => 'categories'],

            // Brand Management
            ['name' => 'view-brands', 'display_name' => 'View Brands', 'group' => 'brands'],
            ['name' => 'create-brand', 'display_name' => 'Create Brand', 'group' => 'brands'],
            ['name' => 'edit-brand', 'display_name' => 'Edit Brand', 'group' => 'brands'],
            ['name' => 'delete-brand', 'display_name' => 'Delete Brand', 'group' => 'brands'],

            // Order Management
            ['name' => 'view-orders', 'display_name' => 'View Orders', 'group' => 'orders'],
            ['name' => 'create-order', 'display_name' => 'Create Order', 'group' => 'orders'],
            ['name' => 'edit-order', 'display_name' => 'Edit Order', 'group' => 'orders'],
            ['name' => 'delete-order', 'display_name' => 'Delete Order', 'group' => 'orders'],
            ['name' => 'cancel-order', 'display_name' => 'Cancel Order', 'group' => 'orders'],

            // Customer Management
            ['name' => 'view-customers', 'display_name' => 'View Customers', 'group' => 'customers'],
            ['name' => 'create-customer', 'display_name' => 'Create Customer', 'group' => 'customers'],
            ['name' => 'edit-customer', 'display_name' => 'Edit Customer', 'group' => 'customers'],
            ['name' => 'delete-customer', 'display_name' => 'Delete Customer', 'group' => 'customers'],

            // Reports & Analytics
            ['name' => 'view-reports', 'display_name' => 'View Reports', 'group' => 'reports'],
            ['name' => 'view-sales-reports', 'display_name' => 'View Sales Reports', 'group' => 'reports'],
            ['name' => 'view-inventory-reports', 'display_name' => 'View Inventory Reports', 'group' => 'reports'],
            ['name' => 'view-profit-reports', 'display_name' => 'View Profit Reports', 'group' => 'reports'],
            ['name' => 'export-reports', 'display_name' => 'Export Reports', 'group' => 'reports'],

            // Inventory Management
            ['name' => 'view-inventory', 'display_name' => 'View Inventory', 'group' => 'inventory'],
            ['name' => 'adjust-inventory', 'display_name' => 'Adjust Inventory', 'group' => 'inventory'],
            ['name' => 'transfer-inventory', 'display_name' => 'Transfer Inventory', 'group' => 'inventory'],
        ];

        // Create permissions (use firstOrCreate to avoid duplicates)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Define role permissions
        $rolePermissions = [
            'super_admin' => [
                // Super Admin has ALL permissions
                'view-system-settings', 'manage-system-settings', 'view-all-business-owners',
                'view-all-stores', 'view-all-users', 'access-any-data',
                'view-stores', 'create-store', 'edit-store', 'delete-store', 'view-store-details',
                'view-users', 'create-user', 'edit-user', 'delete-user', 'assign-user-to-store',
                'view-products', 'create-product', 'edit-product', 'delete-product', 'view-product-details',
                'import-products', 'export-products',
                'view-categories', 'create-category', 'edit-category', 'delete-category',
                'view-brands', 'create-brand', 'edit-brand', 'delete-brand',
                'view-orders', 'create-order', 'edit-order', 'delete-order', 'cancel-order',
                'view-customers', 'create-customer', 'edit-customer', 'delete-customer',
                'view-reports', 'view-sales-reports', 'view-inventory-reports', 'view-profit-reports', 'export-reports',
                'view-inventory', 'adjust-inventory', 'transfer-inventory',
            ],
            
            'business_owner' => [
                // Business Owner can manage their own stores, users, and products
                'view-stores', 'create-store', 'edit-store', 'delete-store', 'view-store-details',
                'view-users', 'create-user', 'edit-user', 'delete-user', 'assign-user-to-store',
                'view-products', 'create-product', 'edit-product', 'delete-product', 'view-product-details',
                'import-products', 'export-products',
                'view-categories', 'create-category', 'edit-category', 'delete-category',
                'view-brands', 'create-brand', 'edit-brand', 'delete-brand',
                'view-orders', 'create-order', 'edit-order', 'delete-order', 'cancel-order',
                'view-customers', 'create-customer', 'edit-customer', 'delete-customer',
                'view-reports', 'view-sales-reports', 'view-inventory-reports', 'view-profit-reports', 'export-reports',
                'view-inventory', 'adjust-inventory', 'transfer-inventory',
            ],
            
            'admin' => [
                // Store Admin can manage products, orders, customers in assigned stores
                'view-store-details',
                'view-products', 'create-product', 'edit-product', 'delete-product', 'view-product-details',
                'import-products', 'export-products',
                'view-categories', 'create-category', 'edit-category',
                'view-brands', 'create-brand', 'edit-brand',
                'view-orders', 'create-order', 'edit-order', 'cancel-order',
                'view-customers', 'create-customer', 'edit-customer',
                'view-reports', 'view-sales-reports', 'view-inventory-reports',
                'view-inventory', 'adjust-inventory',
            ],
            
            'staff' => [
                // Store Staff has limited access (POS operations, view products)
                'view-products', 'view-product-details',
                'view-categories',
                'view-brands',
                'view-orders', 'create-order',
                'view-customers', 'create-customer',
                'view-inventory',
            ],
        ];

        // Assign permissions to roles (use insertOrIgnore to avoid duplicates)
        foreach ($rolePermissions as $role => $permissionNames) {
            foreach ($permissionNames as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    DB::table('role_permissions')->insertOrIgnore([
                        'role' => $role,
                        'permission_id' => $permission->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('✅ Permissions created and assigned to roles successfully!');
    }
}

