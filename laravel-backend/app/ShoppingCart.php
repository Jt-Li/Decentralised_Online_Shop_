<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $fillable = [
        'product_id', 'quantity', 'created_by'
    ];

    public function phone()
    {
        return $this->hasOne('App\Phone');
    }

}
