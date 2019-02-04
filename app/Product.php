<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     definition="Product",
 *     type="object",
 *  @SWG\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="name",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="price",
 *      type="number"
 *  ),
 *     @SWG\Property(
 *      property="currency",
 *      type="string"
 *  ),
 *     @SWG\Property(
 *      property="create_at",
 *      type="string"
 *  ),
 *     @SWG\Property(
 *      property="update_at",
 *      type="string"
 *  ),
 * )
 */
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
