<?php

namespace Database\Seeders;

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
        $role = Role::create([
            'name' => 'Warehouse Person 1',
            'guard_name' => 'web',
        ]);
        $role2 = Role::create([
            'name' => 'Warehouse Person 2',
            'guard_name' => 'web',
        ]);


        $role->givePermissionTo('View Received Products List');
        $role->givePermissionTo('View Packaging List');
        $role->givePermissionTo('View Ready to Ship List');

        $role2->givePermissionTo('View Received Products List');
        $role2->givePermissionTo('View Packaging List');
        $role2->givePermissionTo('View Ready to Ship List');


    }
}
