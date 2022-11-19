<?php

namespace App;

class Discount
{
    public $discountedAmount;

    public $everyN;

    public function __construct($discountedAmount, $everyN)
    {
        $this->discountedAmount = $discountedAmount;
        $this->everyN = $everyN;
    }
}
