<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    public function product(){
        return $this->belongsTo('App\Product');
    }

    protected $fillable = [
        'product_id', 'sku', 'size', 'price', 'stock',
    ];
}
