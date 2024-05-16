<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UsersRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assuming the user with email jdpelaez68@gmail.com exists and has id  1
        // And the roles with ids  1 (for superadmin) and  2 (for instructor) exist
        DB::table('users_roles')->insert([
            ['user_id' =>  1, 'rol_id' =>  1], // Assign superadmin role to the user
        ]);
    }
}
