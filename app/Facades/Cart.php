<?php

namespace App\Facades;

use App\CartImplementation;
use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CartImplementation::class;
    }
}



