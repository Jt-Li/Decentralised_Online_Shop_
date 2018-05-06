<?php

use App\Product;
use App\User;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach (\App\User::get() as $user){
            $product = new Product();
            $product->owner_id = $user->id;
            $product->quantity = 5;
            $product->image_url = 'https://www.google.com.au/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png';
            $product->description = str_random(20);
            $product->name = str_random(10);
            $product->category_id = 1;
            $product->price = 0.002;
            $product->save();
        }
        foreach (\App\User::get() as $user){
            $product = new Product();
            $product->owner_id = $user->id;
            $product->quantity = 5;
            $product->image_url = 'https://www.google.com.au/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png';
            $product->description = str_random(20);
            $product->name = str_random(10);
            $product->category_id = 1;
            $product->price = 0.002;
            $product->save();
        }
    }
}
