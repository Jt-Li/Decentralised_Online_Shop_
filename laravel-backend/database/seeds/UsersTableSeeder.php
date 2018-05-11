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
            'name' => 'Rex',
            'email' => 'test@gmail.com',
            'address' => '0xD7f56927b3d89ac99622dDbFad9372C503D883Ef'
        ]);

        User::create([
            'name' => 'JT1',
            'email' => 'test2@gmail.com',
            'address' => '0xE43Ddc0887818Cc2715a99F2cD98d422B603c55a'
        ]);
        User::create([
            'name' => 'JT2',
            'email' => 'test3@gmail.com',
            'address' => '0x997d5cd92af4A320A99047cC64f9ae1C985Efb7E'
        ]);

        User::create([
            'name' => 'Aaron',
            'email' => 'test4@gmail.com',
            'address' => '0x77346ca9e39DB4740f5f0C23A4b548157CC7Bfe8'
        ]);

    }
}
