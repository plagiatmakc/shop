<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     definition="Category",
 *     type="object",
 *  @SWG\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="title",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="description",
 *      type="string"
 *  ),
 *     @SWG\Property(
 *      property="parent_id",
 *      type="integer"
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
class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'parent_id'
    ];


    /**
     * Many to Many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Product');
    }


    /**
     * One to Many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany('App\Category', 'parent_id');
    }


    /**
     * Recursive relation with categories, loads all descendants
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categoriesRecursive()
    {
        return $this->categories()->with('categoriesRecursive');
    }

}
