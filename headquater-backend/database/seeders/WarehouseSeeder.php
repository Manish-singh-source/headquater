<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('warehouses')->insert([
            'name' => 'Baroda W1',
            'type' => 'storage hub',
            'contact_person_name' => 'Technofra',
            'phone' => '9876543214',
            'alt_phone'    => '9876543214',
            'email' => 'example@gmail.com',
            'gst_number' => 'ASDF987SDF',
            'pan_number' => 'ASDF987SDF',
            'address_line_1' => 'MADHAVAN WAREHOUSING LLP,Survey No. 753, Pargana – Kaswar Raja Tehsil- Rajatalab, Khajuri',
            'address_line_2'    => 'MADHAVAN WAREHOUSING LLP,Survey No. 753, Pargana – Kaswar Raja Tehsil- Rajatalab, Khajuri',
            'max_storage_capacity' => '50',
            'country_id' => '101',
            'state_id' => '4017',
            'city_id' => '6',
            'pincode' => '987654',
        ]);
    }
}
