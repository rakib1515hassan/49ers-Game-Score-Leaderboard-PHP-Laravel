<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'f_name' => 'Md Rakib',
            'l_name' => 'Hassan',
            'email' => 'rakib@gmail.com',
            'password' => Hash::make('123456ra'),
            'pos' => 'L',
            'ht' => '5-6',
            'wt' => 80,
            'age' => 26,
            'email_verified_at' => now(),
        ]);
    }
}

