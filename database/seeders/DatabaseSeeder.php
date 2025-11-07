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

        User::first()->assignRole('Super Admin');



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
    }
}
