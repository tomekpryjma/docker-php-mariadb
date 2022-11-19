<?php

namespace App\Products;

use App\Interfaces\Priceable;

class Product implements Priceable
{
    protected $price;

    protected static $name = 'Simple Product';

    protected static $class = Product::class;

    public function __construct($price)
    {
        $this->price = $price;
    }

    public static function getName()
    {
        return get_called_class()::$name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public static function factory($quantity, $price)
    {
        $instances = [];
        $class = get_called_class();
        for ($i = 0; $i < $quantity; $i++) {
            $instances[] = new $class($price);
        }
        return $instances;
    }
}
