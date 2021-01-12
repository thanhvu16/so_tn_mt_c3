<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $checkUser = DB::table('users')->where([
            'username' => 'admin',
        ])->first();

        if (empty($checkUser)) {
            DB::table('users')->insert([
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
            ]);
        }
    }
}
