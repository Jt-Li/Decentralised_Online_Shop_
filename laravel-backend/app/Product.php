<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'quantity', 'image_url', 'description', 'name', 'price', 'category_id', 'owner_id', 'deleted',
    ];

}
