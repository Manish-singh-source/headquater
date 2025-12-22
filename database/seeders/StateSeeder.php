<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get(database_path('data/states.json'));
        $states = json_decode($json);

        // GST state code mapping for India
        $gstCodes = [
            'JK' => '01', // Jammu and Kashmir
            'HP' => '02', // Himachal Pradesh
            'PB' => '03', // Punjab
            'CH' => '04', // Chandigarh
            'UT' => '05', // Uttarakhand
            'HR' => '06', // Haryana
            'DL' => '07', // Delhi
            'RJ' => '08', // Rajasthan
            'UP' => '09', // Uttar Pradesh
            'BR' => '10', // Bihar
            'SK' => '11', // Sikkim
            'AR' => '12', // Arunachal Pradesh
            'NL' => '13', // Nagaland
            'MN' => '14', // Manipur
            'MZ' => '15', // Mizoram
            'TR' => '16', // Tripura
            'ML' => '17', // Meghalaya
            'AS' => '18', // Assam
            'WB' => '19', // West Bengal
            'JH' => '20', // Jharkhand
            'OR' => '21', // Odisha
            'CT' => '22', // Chhattisgarh
            'MP' => '23', // Madhya Pradesh
            'GJ' => '24', // Gujarat
            'DD' => '25', // Daman and Diu
            'DN' => '26', // Dadra and Nagar Haveli
            'MH' => '27', // Maharashtra
            'AP' => '28', // Andhra Pradesh
            'KA' => '29', // Karnataka
            'GA' => '30', // Goa
            'LD' => '31', // Lakshadweep
            'KL' => '32', // Kerala
            'TN' => '33', // Tamil Nadu
            'PY' => '34', // Puducherry
            'AN' => '35', // Andaman and Nicobar Islands
            'TG' => '36', // Telangana
            'AD' => '37', // Andaman and Nicobar Islands (duplicate?)
        ];

        foreach ($states as $state) {
            if ($state->country_id == 101) { // 101 = India
                $code = isset($gstCodes[$state->state_code]) ? $gstCodes[$state->state_code] : null;
                State::updateOrCreate(
                    ['id' => $state->id],
                    [
                        'name' => $state->name,
                        'country_id' => $state->country_id,
                        'iso2' => $state->state_code ?? null,
                        'code' => $code,
                    ]
                );
            }
        }
    }
}
