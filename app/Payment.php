<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = ['charge_id', 'amount', 'currency', 'order_id', 'source_id', 'status'];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
