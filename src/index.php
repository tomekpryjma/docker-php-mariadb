<?php

require __DIR__ . '/vendor/autoload.php';

use App\Discount;
use App\Scanner;
use App\DiscountManager;
use App\Cart;
use App\Receipt;
use App\Products\Strawberry;
use App\Products\Car;
use App\Products\Carrott;
use App\Products\Door;
use App\Products\ToiletPaper;

$cart = new Cart;
$discountManager = new DiscountManager;
$scanner = new Scanner($discountManager);

$discountManager->addDiscount(Strawberry::class, new Discount(3.99, 2));
$discountManager->addDiscount(Carrott::class, new Discount(1.50, 3));
$discountManager->addDiscount(Car::class, new Discount(250, 2)); // BOGOF

$cart->addItems(
    Carrott::factory(109, 0.45),
    Strawberry::factory(5, 1.25),
    ToiletPaper::factory(1, 3.50),
    new Car(250),
    new Door(50.65),
    new Car(250)
);

$scanner->scanning($cart->getItems());
$scanner->calculate();

(new Receipt($scanner))->print();
