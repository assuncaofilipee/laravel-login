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
            'uuid' =>  Str::uuid(36),
            'email' => 'exemplo@gmail.com',
            'name' => 'Exemplo',
            'cpf' => '98364797085',
            'password' => Hash::make('123a123a'),
            'created_at' => now(),
            'updated_at' => now()
         ]);
    }
}
