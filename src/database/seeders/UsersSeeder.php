<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => '松田 智哉',
            'email' => 'tateyokokumin.siegzeon@gmail.com',
            'password' => Hash::make('aaaaaaaa'),
            'role' => 'admin',
            'tel' => '09042853303',
            'accepted' => true,
            // 'remember_token'    => Str::random(10),
          ]);
        // \App\Models\User::factory(50)->create();  // 10個作成ね！
        User::factory()->count(50)->create();
    }

}
