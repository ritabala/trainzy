<?php

namespace App\Observers;

use App\Models\InvoiceDetailTax;

class InvoiceDetailTaxObserver
{
    public function creating(InvoiceDetailTax $invoiceDetailTax): void
    {
        if (gym()) {
            $invoiceDetailTax->gym_id = gym()->id;
        }
    }

    /**
     * Handle the InvoiceDetailTax "created" event.
     */
    public function created(InvoiceDetailTax $invoiceDetailTax): void
    {
        //
    }

    /**
     * Handle the InvoiceDetailTax "updated" event.
     */
    public function updated(InvoiceDetailTax $invoiceDetailTax): void
    {
        //
    }

    /**
     * Handle the InvoiceDetailTax "deleted" event.
     */
    public function deleted(InvoiceDetailTax $invoiceDetailTax): void
    {
        //
    }

    /**
     * Handle the InvoiceDetailTax "restored" event.
     */
    public function restored(InvoiceDetailTax $invoiceDetailTax): void
    {
        //
    }

    /**
     * Handle the InvoiceDetailTax "force deleted" event.
     */
    public function forceDeleted(InvoiceDetailTax $invoiceDetailTax): void
    {
        //
    }
}
