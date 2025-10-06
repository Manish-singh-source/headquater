<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('vendors')->insert([
            [
                'id' => 1,
                'client_name' => 'K.I.Glassware India Pvt. Ltd',
                'contact_name' => 'Heena',
                'phone_number' => '9958622984',
                'email' => 'retail@kiglassindia.com',
                'gst_number' => '06AACCK5877E1ZF',
                'gst_treatment' => 'business_gst',
                'pan_number' => 'AACCK5877E',
                'billing_address' => '11B, 4 K.M Stone,Beri-Sampla Road,Vill.Ismaila, Po Kultana, Sampla, Dist.Rohtak Bahadurgarh',
                'billing_country' => '101',
                'billing_state' => '4007',
                'billing_city' => '944',
                'billing_zip' => '124501',
                'shipping_address' => '11B, 4 K.M Stone,Beri-Sampla Road,Vill.Ismaila, Po Kultana, Sampla, Dist.Rohtak Bahadurgarh',
                'shipping_country' => '101',
                'shipping_state' => '4007',
                'shipping_city' => '944',
                'shipping_zip' => '124501',
                'status' => '1',
                'vendor_code' => 'KIG',
                'created_at' => '2025-08-29 10:14:30',
                'updated_at' => '2025-08-29 10:14:30',
            ],
            [
                'id' => 2,
                'client_name' => 'Neelam',
                'contact_name' => 'Bhavesh',
                'phone_number' => '1234567890',
                'email' => 'keyacc@inovizideas.com',
                'gst_number' => null,
                'gst_treatment' => null,
                'pan_number' => null,
                'billing_address' => 'CLOUDSTORE RETAIL PRIVATE LIMITED Gat No. 43/1-P and 46-P, Village-Bhamboli, Tal-Khed Pune, Maharashtra - 410507, India',
                'billing_country' => '101',
                'billing_state' => '4008',
                'billing_city' => '2047',
                'billing_zip' => '124501',
                'shipping_address' => 'CLOUDSTORE RETAIL PRIVATE LIMITED Gat No. 43/1-P and 46-P, Village-Bhamboli, Tal-Khed Pune, Maharashtra - 410507, India',
                'shipping_country' => '101',
                'shipping_state' => '4008',
                'shipping_city' => '2047',
                'shipping_zip' => '124501',
                'status' => '1',
                'vendor_code' => '123',
                'created_at' => '2025-08-30 05:56:12',
                'updated_at' => '2025-08-30 05:56:12',
            ],
        ]);
    }
}
