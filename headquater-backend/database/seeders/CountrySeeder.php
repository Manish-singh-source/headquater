<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get(database_path('data/countries.json'));
        $countries = json_decode($json);

        foreach ($countries as $country) {
            if ($country->iso2 === 'IN') {
                Country::create([
                    'id'    => $country->id, // important for linking states
                    'name'  => $country->name,
                    'iso2'  => $country->iso2,
                    'iso3'  => $country->iso3,
                ]);
            }
        }
    }
}
