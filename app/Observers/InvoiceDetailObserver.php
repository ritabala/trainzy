<?php

namespace App\Observers;

use App\Models\InvoiceDetail;

class InvoiceDetailObserver
{
    public function creating(InvoiceDetail $invoiceDetail): void
    {
        if (gym()) {
            $invoiceDetail->gym_id = gym()->id;
        }
    }

    /**
     * Handle the InvoiceDetail "created" event.
     */
    public function created(InvoiceDetail $invoiceDetail): void
    {
        //
    }

    /**
     * Handle the InvoiceDetail "updated" event.
     */
    public function updated(InvoiceDetail $invoiceDetail): void
    {
        //
    }

    /**
     * Handle the InvoiceDetail "deleted" event.
     */
    public function deleted(InvoiceDetail $invoiceDetail): void
    {
        //
    }

    /**
     * Handle the InvoiceDetail "restored" event.
     */
    public function restored(InvoiceDetail $invoiceDetail): void
    {
        //
    }

    /**
     * Handle the InvoiceDetail "force deleted" event.
     */
    public function forceDeleted(InvoiceDetail $invoiceDetail): void
    {
        //
    }
}
