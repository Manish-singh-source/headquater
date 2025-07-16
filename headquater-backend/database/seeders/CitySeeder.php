<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get(database_path('data/cities.json'));
        $cities = json_decode($json);

        foreach ($cities as $city) {
            if ($city->country_id == 101) {
                City::create([
                    'name'     => $city->name,
                    'state_id' => $city->state_id,
                ]);
            }
        }
    }
}
