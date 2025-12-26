<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('warehouses')->insert([
            [
                'name' => 'Baroda Warehouse 1',
                'type' => 'Storage Hub',
                'contact_person_name' => 'Rajesh Patel',
                'phone' => '9825012345',
                'alt_phone' => '9825098765',
                'email' => 'baroda.warehouse@technofra.com',
                'gst_number' => '05AAAPG7885R002',
                'pan_number' => 'ABCDE1234F',
                'address_line_1' => 'Plot No. 12, GIDC Industrial Estate, Makarpura',
                'address_line_2' => 'Near ONGC Circle, Baroda, Gujarat',
                'max_storage_capacity' => '100',
                'country_id' => '101',
                'state_id' => '4016',
                'city_id' => '3892',
                'pincode' => '263601',
            ],
            [
                'name' => 'Kandivali Warehouse 2',
                'type' => 'Storage Hub',
                'contact_person_name' => 'Anita Deshmukh',
                'phone' => '9819012345',
                'alt_phone' => '9819098765',
                'email' => 'kandivali.warehouse@technofra.com',
                'gst_number' => '05AAAPG7885R002',
                'pan_number' => 'ABCDE1234F',
                'address_line_1' => 'Gala No. 8, Western Industrial Estate, Charkop',
                'address_line_2' => 'Kandivali West, Mumbai, Maharashtra',
                'max_storage_capacity' => '120',
                'country_id' => '101',
                'state_id' => '4016',
                'city_id' => '3892',
                'pincode' => '263601',
            ],
        ]);
    }
}
