<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 適当なユーザーを作成
        DB::table('users')->insert([
            [
                'name' => 'ユーザー1',
                'email' => 'user1@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // ハッシュ化したパスワード
                'password_reset_token' => Str::random(60),
                'reset_password_expire_at' => now()->addMinutes(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ユーザー2',
                'email' => 'user2@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // ハッシュ化したパスワード
                'password_reset_token' => Str::random(60),
                'reset_password_expire_at' => now()->addMinutes(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ユーザー3',
                'email' => 'user3@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // ハッシュ化したパスワード
                'password_reset_token' => Str::random(60),
                'reset_password_expire_at' => now()->addMinutes(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
