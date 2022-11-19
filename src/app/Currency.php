<?php

namespace App;

class Currency
{
    private $value;

    private static $separator = ',';

    private static $symbol = '€';

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return self::$symbol . number_format($this->value, 2, self::$separator);
    }

    public function __invoke()
    {
        return $this->value;
    }
}
