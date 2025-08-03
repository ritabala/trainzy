<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $taxSummary;

    public function __construct(Invoice $invoice, array $taxSummary)
    {
        $this->invoice = $invoice;
        $this->taxSummary = $taxSummary;
    }

    public function build()
    {
        // Generate PDF
        $pdf = PDF::loadView('pdf.invoice', [
            'invoice' => $this->invoice,
            'taxSummary' => $this->taxSummary
        ]);

        return $this->subject('Invoice #' . $this->invoice->invoice_number)
            ->view('emails.invoice')
            ->attachData($pdf->output(), 'Invoice-' . $this->invoice->invoice_number . '.pdf');
    }
} 