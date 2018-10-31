<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'title', 'description', 'parent_id'
    ];


    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

    public function categories()
    {
        return $this->hasMany('App\Category', 'parent_id');
    }

    // recursive, loads all descendants
    public function categoriesRecursive()
    {
        return $this->categories()->with('categoriesRecursive');
    }

}
