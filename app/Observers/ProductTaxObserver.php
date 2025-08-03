<?php

namespace App\Observers;

use App\Models\ProductTax;

class ProductTaxObserver
{
    public function creating(ProductTax $productTax): void
    {
        if (gym()) {
            $productTax->gym_id = gym()->id;
        }
    }

    /**
     * Handle the ProductTax "created" event.
     */
    public function created(ProductTax $productTax): void
    {
        //
    }

    /**
     * Handle the ProductTax "updated" event.
     */
    public function updated(ProductTax $productTax): void
    {
        //
    }

    /**
     * Handle the ProductTax "deleted" event.
     */
    public function deleted(ProductTax $productTax): void
    {
        //
    }

    /**
     * Handle the ProductTax "restored" event.
     */
    public function restored(ProductTax $productTax): void
    {
        //
    }

    /**
     * Handle the ProductTax "force deleted" event.
     */
    public function forceDeleted(ProductTax $productTax): void
    {
        //
    }
}
