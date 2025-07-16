<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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

        foreach ($states as $state) {
            if ($state->country_id == 101) { // 101 = India
                State::create([
                    'id'         => $state->id, // important for linking cities
                    'name'       => $state->name,
                    'country_id' => $state->country_id,
                    'iso2'       => $state->state_code ?? null,
                ]);
            }
        }
    }
}
