<?php

namespace App\Facades;

use App\CurrencyConverter;
use Illuminate\Support\Facades\Facade;

class Currency extends Facade
{

    protected static function getFacadeAccessor()
    {
        return CurrencyConverter::class;
    }
}
