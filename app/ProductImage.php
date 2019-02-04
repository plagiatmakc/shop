<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    /**
     * @SWG\Definition(
     *     definition="ProductImage",
     *     type="object",
     *  @SWG\Property(
     *      property="id",
     *      type="integer"
     *  ),
     *  @SWG\Property(
     *      property="link_to_file",
     *      type="string"
     *  ),
     *  @SWG\Property(
     *      property="link_to_thumb",
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
    protected $fillable=['product_id', 'link_to_file', 'link_to_thumb'];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
