<?php

namespace App\Livewire\Payment;

use App\Models\Payment;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewPaymentDetails extends Component
{
    public $paymentId;
    public $payment;

    public function mount($paymentId)
    {
        $this->paymentId = $paymentId;
        $this->loadPayment();
    }

    public function loadPayment()
    {
        $this->payment = Payment::with(['user', 'invoice'])
            ->findOrFail($this->paymentId);
    }

    public function downloadPayment()
    {
        $pdf = PDF::loadView('pdf.payment-receipt', [
            'payment' => $this->payment,
            'user' => $this->payment->user,
            'invoice' => $this->payment->invoice
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, "payment-receipt-{$this->payment->id}.pdf");
    }

    public function render()
    {
        return view('livewire.payment.view-payment-details');
    }
}
