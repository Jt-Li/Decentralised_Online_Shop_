<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         Category::create([
            'name' => 'Fashion', ]);

         Category::create([
            'name' => 'Home', ]);

         Category::create([
            'name' => 'Eletronics', ]);

         Category::create([
            'name' => 'Sports', ]);

         Category::create([
            'name' => 'Toys & Media', ]);

         Category::create([
            'name' => 'Other', ]);



    }
}
