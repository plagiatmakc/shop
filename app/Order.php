<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'cart', 'recipient', 'address', 'status_id', 'payment_id', 'ship_id'
    ];

    public function user()
    {
        $this->belongsTo('App\User');
    }

    public function status()
    {
        $this->belongsTo('App\OrderStatus');
    }

    public function payment()
    {
        $this->hasOne('App\Payment');
    }
}
