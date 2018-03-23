<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'user_name' => str_random(10),
            'email' => 'test@gmail.com',
        ]);

        User::create([
            'user_name' => str_random(10),
            'email' => 'test2@gmail.com',
        ]);
    }
}