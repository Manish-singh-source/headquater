<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create([
            'name' => 'Warehouse Person 1',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'Warehouse Person 2',
            'guard_name' => 'web'
        ]);
    }
}
