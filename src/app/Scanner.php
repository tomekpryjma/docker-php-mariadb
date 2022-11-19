<?php

namespace App;

use App\Interfaces\Priceable;

class Scanner
{
    /**
     * Collection of scanned products.
     */
    private $scanned = [];

    private $scannedQuantities = [];

    private $discountManager;

    private $scannedInstances = [];

    private $savings = [];

    private $total = 0;

    private static $spacer = '.';

    public function __construct(DiscountManager $discountManager)
    {
        $this->discountManager = $discountManager;
    }

    public function scanning($items)
    {
        foreach ($items as $product) {
            $this->scan($product);
        }
    }

    public function scan(Priceable $priceable)
    {
        $this->scanned[] = $priceable;
    }

    public function calculate()
    {
        $this->calculateTotal();
        $this->calculateSavings();
    }

    public function getTotal()
    {
        return new Currency($this->total);
    }

    public function getAllSavings()
    {
        return $this->savings;
    }

    public function getSavingsTotal()
    {
        if (!$this->savings) {
            return 0;
        }

        $totalSavings = array_reduce($this->savings, function ($carry, $num) {
            $carry += $num;
            return $carry;
        });

        return new Currency($totalSavings);
    }

    public function getTotalScanned()
    {
        return count($this->scanned);
    }

    public function getTotalAfterDiscounts()
    {
        if (!$this->savings) {
            return 'No money was saved.';
        }

        return new Currency($this->total - $this->getSavingsTotal()());
    }

    public function listScanned()
    {
        $longestNameLength = 0;

        foreach ($this->scannedInstances as $instance) {
            $nameLength = strlen($instance::getName());

            if ($nameLength > $longestNameLength) {
                $longestNameLength = $nameLength;
            }
        }

        $formatted = array_map(function ($product, $quantity) use ($longestNameLength) {
            $name = $product::getName();
            $iterations = Receipt::$width - (strlen($name) + strlen($quantity) + 1); // Account for the 'x' in quantity.

            for ($i = 0; $i <= $iterations; $i++) {
                $name .= self::$spacer;
            }

            $name .= "x$quantity";
            return $name;
        }, array_keys($this->scannedQuantities), array_values($this->scannedQuantities));

        return implode("\n", $formatted);
    }

    private function calculateTotal()
    {
        foreach ($this->scanned as $priceable) {
            $this->total += $priceable->getPrice();
        }
    }

    private function calculateSavings()
    {
        foreach ($this->scanned as $priceable) {
            if (!isset($this->scannedQuantities[$priceable::class])) {
                $this->scannedQuantities[$priceable::class] = 0;
                $this->scannedInstances[$priceable::class] = $priceable;
            }
            $this->scannedQuantities[$priceable::class] += 1;
        }

        foreach ($this->scannedQuantities as $priceable => $quantity) {
            if (!$this->discountManager->discountExists($priceable)) {
                continue;
            }
            $this->savings[$priceable] = $this->discountManager->applyDiscount($this->scannedInstances[$priceable], $quantity);
        }
    }
}
