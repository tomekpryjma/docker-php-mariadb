<?php

use App\Cart;
use App\Currency;
use App\Discount;
use App\DiscountManager;
use App\Products\Car;
use App\Products\Strawberry;
use App\Scanner;

require __DIR__ . '/vendor/autoload.php';

function output($result, $expected, $actual, $test)
{
    if (!$result) {
        echo "\n";
        echo "\e[91mAssertion in test " . $test . ' failed.' . "\e[39m
        \n";
        echo "Expected: $expected\n";
        echo "Actual: $actual\n";
        return;
    }

    echo "\e[32mTest " . $test . " passed!\e[39m\n";
}

function test_discounts_work()
{
    $cart = new Cart;
    $discountManager = new DiscountManager;
    $scanner = new Scanner($discountManager);

    $discountManager->addDiscount(Strawberry::class, new Discount(0.5, 2));


    $cart->addItems(
        Strawberry::factory(5, 1.25),
    );

    $scanner->scanning($cart->getItems());
    $scanner->calculate();

    $expected = new Currency((5 * 1.25) - (0.5 * 2));
    $actual = $scanner->getTotalAfterDiscounts();

    $result = "$actual" === "$expected";

    output($result, "$expected", "$actual", __FUNCTION__);
}

function test_totalling_work()
{
    $cart = new Cart;
    $discountManager = new DiscountManager;
    $scanner = new Scanner($discountManager);


    $cart->addItems(
        Strawberry::factory(5, 1.25),
        Car::factory(2, 500),
    );

    $scanner->scanning($cart->getItems());
    $scanner->calculate();

    $expected = new Currency((5 * 1.25) + (2 * 500));
    $actual = $scanner->getTotal();

    $result = "$actual" === "$expected";

    output($result, "$expected", "$actual", __FUNCTION__);
}

test_discounts_work();
test_totalling_work();
