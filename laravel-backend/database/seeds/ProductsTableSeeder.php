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
        $nameArray1 = ['Iran man 1', 'Piggy Peggy 1', 'Koala 1', 'kangaroo 1'];
        $linkArray1 = ['https://images-na.ssl-images-amazon.com/images/I/71foIt34uHL._SY355_.jpg',
                        'https://i.ebayimg.com/images/g/ssUAAOSw8d5ZWO69/s-l300.png',
                        'https://cdn3.volusion.com/9nxdj.fchy5/v/vspfiles/photos/WR-16228-2.jpg?1504777849',
                        'http://i.ebayimg.com/00/s/NTAwWDUwMA==/z/Qa0AAOxyBvZTWIHW/$_3.JPG?set_id=2'];
        $i = 0;
        foreach (\App\User::get() as $user){
            $product = new Product();
            $product->owner_id = $user->id;
            $product->quantity = 50;
            $product->image_url = $linkArray1[$i];
            $product->description = str_random(20);
            $product->name = $nameArray1[$i];
            $product->category_id = 1;
            $product->price = 0.002;
            $i = $i + 1;
            $product->save();
        }
        $i = 0;
        $nameArray2 = ['Iran man 2', 'Piggy Peggy 2', 'Koala 2', 'kangaroo 2'];
        $linkArray2 = ['https://i.pinimg.com/originals/13/16/8f/13168fcca35a74cd414cdb66d0738d72.jpg',
                        'https://ae01.alicdn.com/kf/HTB14H8zQVXXXXXEaXXXq6xXFXXXI/Piggy-Peggy-plush-toy-doll-small-pendant-cute-little-pig-doll-doll-baby-girlfriend-birthday-gift.jpg_640x640.jpg',
                        'https://shop.australiangeographic.com.au/media/catalog/product/cache/3/image/540x/9df78eab33525d08d6e5fb8d27136e95/B/D/BDEB-plush-koala-25cm-web.jpg',
                        'https://cdn.shopify.com/s/files/1/1446/8412/products/kangaroo-and-joey.jpg?v=1504067840'];
        foreach (\App\User::get() as $user){
            $product = new Product();
            $product->owner_id = $user->id;
            $product->quantity = 50;
            $product->image_url = $linkArray2[$i];
            $product->description = str_random(20);
            $product->name = $nameArray2[$i];
            $product->category_id = 1;
            $product->price = 0.001;
            $i = $i + 1;
            $product->save();
        }
        $i = 0;
        $nameArray3 = ['Iran man 3', 'Piggy Peggy 3', 'Koala 3', 'kangaroo 3'];
        $linkArray3 = ['https://i.pinimg.com/originals/fa/24/08/fa240830d7078ec81d4e995aa2eca095.jpg',
                        'https://ae01.alicdn.com/kf/HTB10nhSQVXXXXbTXFXXq6xXFXXXP/Piggy-Peggy-plush-toy-doll-small-pendant-cute-little-pig-doll-doll-baby-girlfriend-birthday-gift.jpg',
                        'https://images-eu.ssl-images-amazon.com/images/I/517K9RKpg4L._SL500_AC_SS350_.jpg',
                        'https://cdn.shopify.com/s/files/1/2008/6717/products/AD82.jpg?v=1504074048'];
        foreach (\App\User::get() as $user){
            $product = new Product();
            $product->owner_id = $user->id;
            $product->quantity = 50;
            $product->image_url = $linkArray3[$i];
            $product->description = str_random(20);
            $product->name = $nameArray3[$i];
            $product->category_id = 1;
            $product->price = 0.003;
            $i = $i + 1;
            $product->save();
        }
    }
}
