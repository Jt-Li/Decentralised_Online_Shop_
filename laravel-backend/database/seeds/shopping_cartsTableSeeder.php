<?php

use Illuminate\Database\Seeder;
use App\ShoppingCart;
use App\User;
use App\Product;

class shopping_cartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\User::get() as $user) {
        	foreach (\App\Product::get() as $product) {
        		$ShoppingCart = new ShoppingCart();
        		$ShoppingCart->created_by = $user->id;
        		$ShoppingCart->product_id = $product->id;
        		$ShoppingCart->quantity = rand(1,$product->quantity);
        		$ShoppingCart->save();
        	}
        }
    }
}
