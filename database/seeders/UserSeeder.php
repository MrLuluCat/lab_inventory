<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'SPV Inventory',
            'email' => 'spv_inventory@example.com',
            'password' => Hash::make('spv_inventory'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
