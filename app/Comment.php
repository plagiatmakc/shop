<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
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
