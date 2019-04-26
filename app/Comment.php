<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     definition="Comment",
 *     type="object",
 *  @SWG\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="user_id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="body",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="commentable_id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="commentable_type",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="create_at",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="update_at",
 *      type="string"
 *  ),
 * )
 */
class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=['body','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
                    ->with(['user' => function ($query) {
                             $query->select('id', 'first_name', 'last_name', 'avatar');
                    }]);
    }

    public function commentsRecursive()
    {
        return $this->comments()->with('commentsRecursive');
    }
}
