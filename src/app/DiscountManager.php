<?php

namespace App;

use App\Discount;
use App\Interfaces\Priceable;

class DiscountManager
{
    private $discounts = [];

    public function addDiscount(string $priceable, Discount $discount)
    {
        if (!isset($this->discounts[$priceable])) {
            $this->discounts[$priceable] = [];
        }
        $this->discounts[$priceable][] = $discount;
        return $this;
    }

    public function discountExists(string $priceable)
    {
        return isset($this->discounts[$priceable]);
    }

    public function applyDiscount(Priceable $priceable, $quantity)
    {
        $total = 0;
        foreach ($this->discounts[$priceable::class] as $discount) {
            $applyTimes = intval(floor($quantity / $discount->everyN));
            $total += $applyTimes * $discount->discountedAmount;
        }
        return $total;
    }
}
