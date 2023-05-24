<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserAdminSeeder extends Seeder
{
    const TABLE_NAME = "user";
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // add admin to user table
        $data = array(
            array(
                'username' => 'admin',
                'email' => 'admin@growmodo.com',
                'phone_number' => '09198193427',
                'password' => 'growmodoadmin',
                'designation' => 1, // 1 as admin
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ),
        );

        foreach ($data as $userData) {
            DB::table(self::TABLE_NAME)->insert($userData);
        }
    }
}
