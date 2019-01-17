<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'cart', 'recipient', 'address', 'status_id', 'payment_id', 'ship_id'
    ];

    const PENDING_PAYMENT = 1;
    const AWAITING_FULFILLMENT = 2;
    const AWAITING_SHIPMENT = 3;

    public static function getStatusesList()
    {
        return [
            1 => 'PENDING_PAYMENT',
            2 => 'AWAITING_FULFILLMENT',
            3 => 'AWAITING_SHIPMENT'
        ];
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function status()
    {
       return $this->belongsTo('App\OrderStatus');
    }

    public function payment()
    {
       return $this->hasOne('App\Payment', 'order_id');
    }

    /**
     * @return bool
     */
    public function setStatus($status): bool
    {
        return $this->update(['status_id' => $status]);
    }
}
