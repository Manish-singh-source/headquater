<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'warehouse_id' => 1,
            'user_name' => 'wp1',
            'fname' => 'Roshan',
            'lname' => 'Yadav',
            'phone' => '9876543210',
            'password' => bcrypt('123456'),
            'dob' => '1990-02-15',
            'marital' => 'Single',
            'gender' => 'Male',
            'email' => 'w1@gmail.com',
            'current_address' => 'B-102, Lake View Apartments, Kandivali East',
            'permanent_address' => 'B-102, Lake View Apartments, Kandivali East',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'country' => 'India',
            'pincode' => '400101',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::create([
            'warehouse_id' => 2,
            'user_name' => 'wp2',
            'fname' => 'Manish',
            'lname' => 'Singh',
            'phone' => '9966123456',
            'password' => bcrypt('123456'),
            'dob' => '1994-07-21',
            'marital' => 'Married',
            'gender' => 'Male',
            'email' => 'w2@gmail.com',
            'current_address' => '201, Vishal CHS, Malad West',
            'permanent_address' => '201, Vishal CHS, Malad West',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'country' => 'India',
            'pincode' => '400095',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
