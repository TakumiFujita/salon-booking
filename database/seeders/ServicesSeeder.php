<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'name' => 'カット',
                'duration' => '60',
                'price' => '3500',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'カット&カラー',
                'duration' => '120',
                'price' => '7000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'カット&パーマ',
                'duration' => '150',
                'price' => '8000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'シャンプー&ブロー',
                'duration' => '30',
                'price' => '2000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'トリートメント',
                'duration' => '30',
                'price' => '3000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
