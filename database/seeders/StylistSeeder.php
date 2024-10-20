<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stylists')->insert([
            [
                'name' => 'スタイリストA',
                'email' => 'a@mail.com',
                'password' => Hash::make('test'),
                'role_id' => 1,
                'introduction' => '得意なスタイルはショートカットです。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'スタイリストB',
                'email' => 'b@mail.com',
                'password' => Hash::make('test'),
                'role_id' => 2,
                'introduction' => 'カラーリングが得意です。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
