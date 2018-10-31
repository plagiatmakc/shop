<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function product_attributes(){
        return $this->hasMany('App\ProductAttributes');
    }

    public function categories(){
        return $this->belongsToMany('App\Category');
    }

    protected $fillable = [
        'name', 'price', 'currency',
    ];


}
