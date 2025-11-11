<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
    /**
     * Helper method to update or create permission with group ID
     */
    private function updateOrCreatePermission($name, $groupId)
    {
        $permission = Permission::where('name', $name)->first();
        if ($permission) {
            $permission->update(['permission_group_id' => $groupId]);
        } else {
            Permission::create(['name' => $name, 'permission_group_id' => $groupId]);
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permission Groups and update existing permissions with group IDs

        // Dashboard Group
        $dashboardGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Dashboard'],
            ['description' => 'Dashboard access permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Dashboard', $dashboardGroup->id);

        // Access Control Group
        $accessControlGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Access Control'],
            ['description' => 'Access control and permissions management', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Access Control', $accessControlGroup->id);

        // Manage Staffs Group
        $staffsGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Staffs'],
            ['description' => 'Staff management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Staffs', $staffsGroup->id);
        $this->updateOrCreatePermission('Add Staffs', $staffsGroup->id);
        $this->updateOrCreatePermission('Edit Staffs', $staffsGroup->id);
        $this->updateOrCreatePermission('Delete Staffs', $staffsGroup->id);
        $this->updateOrCreatePermission('Change User Status', $staffsGroup->id);
        $this->updateOrCreatePermission('Multi Select Delete Staffs', $staffsGroup->id);
        $this->updateOrCreatePermission('Multi Select Change User Status', $staffsGroup->id);

        // Manage Roles Group
        $rolesGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Roles'],
            ['description' => 'Role management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Roles', $rolesGroup->id);
        $this->updateOrCreatePermission('Add Roles', $rolesGroup->id);
        $this->updateOrCreatePermission('Edit Roles', $rolesGroup->id);
        $this->updateOrCreatePermission('Delete Roles', $rolesGroup->id);

        // Manage Master Group
        $masterGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Master'],
            ['description' => 'Master data management permissions', 'status' => 1]
        );  
        $this->updateOrCreatePermission('View Master', $masterGroup->id);   

        // Manage Customer Groups
        $customerGroupsGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Customer Groups'],
            ['description' => 'Customer group management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Customer Groups', $customerGroupsGroup->id);
        $this->updateOrCreatePermission('Add Customer Groups', $customerGroupsGroup->id);
        $this->updateOrCreatePermission('Edit Customer Groups', $customerGroupsGroup->id);
        $this->updateOrCreatePermission('Delete Customer Groups', $customerGroupsGroup->id);
        $this->updateOrCreatePermission('Change Customer Group Status', $customerGroupsGroup->id);
        $this->updateOrCreatePermission('Multi Select Delete Customer Groups', $customerGroupsGroup->id);
        $this->updateOrCreatePermission('Multi Select Change Customer Group Status', $customerGroupsGroup->id);

        // Manage Customers 
        $customersInGroupsGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Customers'],
            ['description' => 'Customer management', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Customers', $customersInGroupsGroup->id);
        $this->updateOrCreatePermission('Add Customers', $customersInGroupsGroup->id);
        $this->updateOrCreatePermission('Edit Customers', $customersInGroupsGroup->id);
        $this->updateOrCreatePermission('Delete Customers', $customersInGroupsGroup->id);
        $this->updateOrCreatePermission('Multi Select Delete Customers', $customersInGroupsGroup->id);
        $this->updateOrCreatePermission('Multi Select Change Customer Status', $customersInGroupsGroup->id);

        // Manage Vendors Group
        $vendorsGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Vendors'],
            ['description' => 'Vendor management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Vendors', $vendorsGroup->id);
        $this->updateOrCreatePermission('Add Vendors', $vendorsGroup->id);
        $this->updateOrCreatePermission('Edit Vendors', $vendorsGroup->id);
        $this->updateOrCreatePermission('Delete Vendors', $vendorsGroup->id);
        $this->updateOrCreatePermission('Multi Select Delete Vendors', $vendorsGroup->id);
        $this->updateOrCreatePermission('Multi Select Change Vendor Status', $vendorsGroup->id);

        // Manage Products Group
        $productsGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Products'],
            ['description' => 'Product management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Products', $productsGroup->id);
        $this->updateOrCreatePermission('Add Products', $productsGroup->id);
        $this->updateOrCreatePermission('Edit Products', $productsGroup->id);
        $this->updateOrCreatePermission('Delete Products', $productsGroup->id);
        $this->updateOrCreatePermission('Multi Select Delete Products', $productsGroup->id);
        $this->updateOrCreatePermission('Multi Select Change Product Status', $productsGroup->id);
        $this->updateOrCreatePermission('Download Products Excel Format', $productsGroup->id);
        $this->updateOrCreatePermission('Upload Products Excel Format', $productsGroup->id);

        // Manage SKU Mapping Group
        $skuMappingGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage SKU Mapping'],
            ['description' => 'SKU mapping management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View SKU Mapping', $skuMappingGroup->id);
        $this->updateOrCreatePermission('Add SKU Mapping', $skuMappingGroup->id);
        $this->updateOrCreatePermission('Edit SKU Mapping', $skuMappingGroup->id);
        $this->updateOrCreatePermission('Delete SKU Mapping', $skuMappingGroup->id);
        $this->updateOrCreatePermission('Multi Select Delete SKU Mapping', $skuMappingGroup->id);
        $this->updateOrCreatePermission('Download SKU Mapping Excel Format', $skuMappingGroup->id);
        $this->updateOrCreatePermission('Upload SKU Mapping Excel Format', $skuMappingGroup->id);

        // Manage Warehouses Group
        $warehousesGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Warehouses'],
            ['description' => 'Warehouse management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Warehouses', $warehousesGroup->id);
        $this->updateOrCreatePermission('Add Warehouses', $warehousesGroup->id);
        $this->updateOrCreatePermission('Edit Warehouses', $warehousesGroup->id);
        $this->updateOrCreatePermission('Delete Warehouses', $warehousesGroup->id);
        $this->updateOrCreatePermission('Multi Select Delete Warehouses', $warehousesGroup->id);
        $this->updateOrCreatePermission('Multi Select Change Warehouse Status', $warehousesGroup->id);
        $this->updateOrCreatePermission('Download Warehouse Excel Format', $warehousesGroup->id);
        $this->updateOrCreatePermission('Upload Warehouse Excel Format', $warehousesGroup->id);

        // Manage Purchase Group
        $purchaseOrdersGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Purchase Orders'],
            ['description' => 'Purchase order management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Purchase Orders List', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Add Purchase Orders', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Delete Purchase Orders', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Multi Select Delete Purchase Orders', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('View Purchase Order', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Change Purchase Order Status', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Add Vendor GRN', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Add vendor invoice', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Add vendor payment', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Export Vendor PO', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Update Vendor PO', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Multi Delete Vendor PO Product', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Delete Purchase Order Product', $purchaseOrdersGroup->id);
        $this->updateOrCreatePermission('Approve/Reject Vendor PI', $purchaseOrdersGroup->id);

        // Manage Sales Group
        $salesOrdersGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Sales Orders'],
            ['description' => 'Sales order management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Sales Orders List', $salesOrdersGroup->id);
        $this->updateOrCreatePermission('Add Sales Orders', $salesOrdersGroup->id);
        $this->updateOrCreatePermission('Check Availibility', $salesOrdersGroup->id);
        $this->updateOrCreatePermission('Store Sales Order', $salesOrdersGroup->id);
        $this->updateOrCreatePermission('Delete Sales Orders', $salesOrdersGroup->id);
        $this->updateOrCreatePermission('View Sales Order', $salesOrdersGroup->id);
        $this->updateOrCreatePermission('Change Sales Order Status', $salesOrdersGroup->id);
        $this->updateOrCreatePermission('Multi Select Delete Sales Orders', $salesOrdersGroup->id);
        $this->updateOrCreatePermission('Export Customer PO', $salesOrdersGroup->id);
        $this->updateOrCreatePermission('Update Customer PO', $salesOrdersGroup->id);
        $this->updateOrCreatePermission('Generate Invoice', $salesOrdersGroup->id);

        // Manage Received Products Group
        $receivedProductsGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Received Products'],
            ['description' => 'Received products management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Received Products List', $receivedProductsGroup->id);
        $this->updateOrCreatePermission('View Received Products', $receivedProductsGroup->id);
        $this->updateOrCreatePermission('Download Received Products Excel Format', $receivedProductsGroup->id);
        $this->updateOrCreatePermission('Upload Received Products Excel Format', $receivedProductsGroup->id);

        // Manage Packaging List Group
        $packagingListGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Packaging List'],
            ['description' => 'Packaging list management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Packaging List', $packagingListGroup->id);
        $this->updateOrCreatePermission('View Packaging Products', $packagingListGroup->id);
        $this->updateOrCreatePermission('Download Packaging Products Excel Format', $packagingListGroup->id);
        $this->updateOrCreatePermission('Upload Packaging Products Excel Format', $packagingListGroup->id);
        $this->updateOrCreatePermission('Change Status to Ready to Ship', $packagingListGroup->id);

        // Manage Ready to Ship Group
        $readyToShipGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Ready to Ship'],
            ['description' => 'Ready to ship management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Ready to Ship List', $readyToShipGroup->id);
        $this->updateOrCreatePermission('View Ready to Ship Products', $readyToShipGroup->id);
        $this->updateOrCreatePermission('Change Status to Shipped', $readyToShipGroup->id); 

        // Manage Product Issues 
        $productIssuesGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Product Issues'],
            ['description' => 'Product issues management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Product Issues', $productIssuesGroup->id);
        $this->updateOrCreatePermission('Accept/Reject Vendor Exceed/Shortage Products', $productIssuesGroup->id);

        // Manage Vendor Return 
        $vendorReturnGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Vendor Return'],
            ['description' => 'Vendor return management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Vendor Return Products', $vendorReturnGroup->id);
        $this->updateOrCreatePermission('Change Status to Accept/Return', $vendorReturnGroup->id);

        // Manage Track Order 
        $trackOrderGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Manage Track Order'],
            ['description' => 'Track order management permissions', 'status' => 1]
        );
        $this->updateOrCreatePermission('View Track Order', $trackOrderGroup->id);
        
    }
}
