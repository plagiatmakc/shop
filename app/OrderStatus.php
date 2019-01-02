<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $fillable = [
        'status', 'description'
    ];

    public function orders()
    {
        $this->hasMany('App\Order', 'status_id');
    }
}
