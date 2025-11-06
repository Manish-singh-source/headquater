<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Manage Staffs
        Permission::create(['name' => 'View Staffs']);
        Permission::create(['name' => 'Add Staffs']);
        Permission::create(['name' => 'Edit Staffs']);
        Permission::create(['name' => 'Delete Staffs']);
        Permission::create(['name' => 'Change User Status']);
        Permission::create(['name' => 'Multi Select Delete Staffs']);
        Permission::create(['name' => 'Multi Select Change User Status']);

        // Manage Roles
        Permission::create(['name' => 'View Roles']);
        Permission::create(['name' => 'Add Roles']);
        Permission::create(['name' => 'Edit Roles']);
        Permission::create(['name' => 'Delete Roles']);

        // Manage Customer Groups
        Permission::create(['name' => 'View Customer Groups']);
        Permission::create(['name' => 'Add Customer Groups']);
        Permission::create(['name' => 'Edit Customer Groups']);
        Permission::create(['name' => 'Delete Customer Groups']);
        Permission::create(['name' => 'Change Customer Group Status']);
        Permission::create(['name' => 'Multi Select Delete Customer Groups']);
        Permission::create(['name' => 'Multi Select Change Customer Group Status']);

        // Manage Customers Group View
        Permission::create(['name' => 'Groups View Customers']);
        Permission::create(['name' => 'Groups Add Customers']);
        Permission::create(['name' => 'Groups Edit Customers']);
        Permission::create(['name' => 'Groups Delete Customers']);
        Permission::create(['name' => 'Groups Multi Select Delete Customers']);
        Permission::create(['name' => 'Groups Multi Select Change Customer Status']);

        // Manage Vendors 
        Permission::create(['name' => 'View Vendors']);
        Permission::create(['name' => 'Add Vendors']);
        Permission::create(['name' => 'Edit Vendors']);
        Permission::create(['name' => 'Delete Vendors']);
        Permission::create(['name' => 'Multi Select Delete Vendors']);
        Permission::create(['name' => 'Multi Select Change Vendor Status']);

        // Manage Products 
        Permission::create(['name' => 'View Products']);
        Permission::create(['name' => 'Add Products']);
        Permission::create(['name' => 'Edit Products']);
        Permission::create(['name' => 'Delete Products']);
        Permission::create(['name' => 'Multi Select Delete Products']);
        Permission::create(['name' => 'Multi Select Change Product Status']);
        Permission::create(['name' => 'Download Products Excel Format']);
        Permission::create(['name' => 'Upload Products Excel Format']);

        // Manage SKU Mapping 
        Permission::create(['name' => 'View SKU Mapping']);
        Permission::create(['name' => 'Add SKU Mapping']);
        Permission::create(['name' => 'Edit SKU Mapping']);
        Permission::create(['name' => 'Delete SKU Mapping']);
        Permission::create(['name' => 'Multi Select Delete SKU Mapping']);
        Permission::create(['name' => 'Download SKU Mapping Excel Format']);
        Permission::create(['name' => 'Upload SKU Mapping Excel Format']);

        // Manage Warehouses 
        Permission::create(['name' => 'View Warehouses']);
        Permission::create(['name' => 'Add Warehouses']);
        Permission::create(['name' => 'Edit Warehouses']);
        Permission::create(['name' => 'Delete Warehouses']);
        Permission::create(['name' => 'Multi Select Delete Warehouses']);
        Permission::create(['name' => 'Multi Select Change Warehouse Status']);
        Permission::create(['name' => 'Download Warehouse Excel Format']);
        Permission::create(['name' => 'Upload Warehouse Excel Format']);

        // Manage Purchase Orders 
        Permission::create(['name' => 'View Purchase Orders']);
        Permission::create(['name' => 'Add Purchase Orders']);
        Permission::create(['name' => 'Edit Purchase Orders']);
        Permission::create(['name' => 'Delete Purchase Orders']);
        Permission::create(['name' => 'Multi Select Delete Purchase Orders']);
        Permission::create(['name' => 'Multi Select Change Purchase Order Status']);

        // Manage Purchase Order Products 
        Permission::create(['name' => 'View Purchase Order Products']);
        Permission::create(['name' => 'Add Purchase Order Products']);
        Permission::create(['name' => 'Edit Purchase Order Products']);
        Permission::create(['name' => 'Delete Purchase Order Products']);
        Permission::create(['name' => 'Multi Select Delete Purchase Order Products']);
        Permission::create(['name' => 'Download Purchase Order Products Excel Format']);
        Permission::create(['name' => 'Upload Purchase Order Products Excel Format']);
        Permission::create(['name' => 'Upload Vendor GRN']);
        Permission::create(['name' => 'Upload Vendor Invoice']);
        Permission::create(['name' => 'Upload Vendor Payment']);
    }
}
