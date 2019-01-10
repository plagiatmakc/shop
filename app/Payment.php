<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = ['charge_id', 'transaction_id', 'amount', ];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
