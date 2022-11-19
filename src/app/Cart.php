<?php

namespace App;

class Cart
{
    private $items = [];

    /**
     * Add items to the cart. You can pass in arrays of products or individual products.
     * 
     * @param mixed
     */
    public function addItems()
    {
        $addedItems = func_get_args();

        foreach ($addedItems as $addedItem) {
            if (is_array($addedItem)) {
                $this->items = array_merge($this->items, $addedItem);
            } else {
                $this->items[] = $addedItem;
            }
        }
    }
    public function getItems()
    {
        return $this->items;
    }
}
