<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //	name	slug	permissions
        DB::table('roles')->insert([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'permissions' => '{"view_dashboard":"view_dashboard","view_dashboard_detail":"view_dashboard_detail","view_dashboard_table":"view_dashboard_table","view_admin":"view_admin","update_profile":"update_profile","create_admin":"create_admin","update_admin":"update_admin","delete_admin":"delete_admin","view_staff":"view_staff","create_staff":"create_staff","update_staff":"update_staff","delete_staff":"delete_staff","view_roles":"view_roles","create_roles":"create_roles","update_roles":"update_roles","delete_roles":"delete_roles","view_customer":"view_customer","view_customer-detail":"view_customer-detail","create_customer":"create_customer","update_customer":"update_customer","delete_customer":"delete_customer","view_vendor":"view_vendor","view_vendor-detail":"view_vendor-detail","create_vendor":"create_vendor","update_vendor":"update_vendor","delete_vendor":"delete_vendor","view_product":"view_product","create_product":"create_product","update_product":"update_product","delete_product":"delete_product","view_warehouse":"view_warehouse","view_warehouse-detail":"view_warehouse-detail","create_warehouse":"create_warehouse","update_warehouse":"update_warehouse","delete_warehouse":"delete_warehouse","view_purchase_order":"view_purchase_order","view_purchase_order_detail":"view_purchase_order_detail","create_purchase_order":"create_purchase_order","update_purchase_order":"update_purchase_order","delete_purchase_order":"delete_purchase_order","view_sale":"view_sale","view_sale-detail":"view_sale-detail","create_sale":"create_sale","update_sale":"update_sale","delete_sale":"delete_sale","view_invoice":"view_invoice","view_invoice-detail":"view_invoice-detail","create_invoice":"create_invoice","update_invoice":"update_invoice","payment_invoice":"payment_invoice","view_received_products":"view_received_products","update_received_products":"update_received_products","view_packaging_list":"view_packaging_list","view_packaging_detail":"view_packaging_detail","view_ready_to_ship":"view_ready_to_ship","view_ready_to_ship_detail":"view_ready_to_ship_detail","view_track_order":"view_track_order"}',
        ]);

        DB::table('staff')->insert([
            'role_id' => 1,
            'user_name' => 'admin',
            'fname' => 'admin',
            'lname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
