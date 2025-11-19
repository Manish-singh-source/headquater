<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        User::create([
            'warehouse_id' => 0,
            'user_name' => 'superadmin',
            'fname' => 'Super',
            'lname' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => '123456',
        ]);

        Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        User::find(1)->assignRole('Super Admin');

        User::create([
            'warehouse_id' => 0,
            'user_name' => 'Technofra',
            'fname' => 'Technofra',
            'lname' => 'Admin',
            'email' => 'support@technofra.com',
            'password' => 'Technofra@1021',
        ]);

        Role::create([
            'name' => 'Super Admin 2',
            'guard_name' => 'web',
        ]);

        User::find(2)->assignRole('Super Admin 2');

        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            WarehouseSeeder::class,
            VendorsSeeder::class,
            PermissionsSeeder::class,
            RolesTableSeeder::class,
            UsersTableSeeder::class,
        ]);




        User::find(3)->assignRole('Warehouse Person 1');
        User::find(4)->assignRole('Warehouse Person 2');

    }
}
