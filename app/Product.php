<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'currency',
    ];

    public function product_attributes(){
        return $this->hasMany('App\ProductAttributes');
    }

    public function categories(){
        return $this->belongsToMany('App\Category');
    }

    public function  product_images()
    {
        return $this->hasMany('App\ProductImage');
    }


}
