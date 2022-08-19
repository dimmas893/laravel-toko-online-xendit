<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@mail.com',
            'level' => 'ADMIN',
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(32),
        ]);

        DB::table('users')->insert([
            'name' => 'CEO',
            'username' => 'ceo',
            'email' => 'ceo@mail.com',
            'level' => 'CEO',
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(32),
        ]);

        DB::table('users')->insert([
            'name' => 'STAFF',
            'username' => 'staff',
            'email' => 'staff@mail.com',
            'level' => 'STAFF',
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(32),
        ]);
    }
}
