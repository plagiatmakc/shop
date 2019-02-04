<?php

namespace App;

class CurrencyConverter
{

    protected $currencies = [
        "usd" => 1,
        "eur" => 0.75,
        "uah" => 28.2
    ];

    public function convert($price, $current = null, $need = null)
    {
        $from_rate = $this->currencies[$current];
        $to_rate = $this->currencies[$need];
        $price = $price * $to_rate / $from_rate;

        return ceil($price*100)/100;
    }

}
