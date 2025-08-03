<?php

namespace App\Livewire\Invoice;

use App\Models\Invoice;
use App\Mail\InvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ViewInvoiceDetails extends Component
{
    use LivewireAlert;

    public $invoice;
    public $invoiceId;
    public $taxSummary = [];
    public $showPaymentModal = false;
    public $paymentAmount = 0;
    public $paymentMode = 'cash';
    public $paymentModeOptions = [];


    protected $listeners = ['sendInvoice'];
    
    public function mount($invoiceId)
    {
        $this->invoiceId = $invoiceId;
        $this->loadInvoice();
        $this->calculateTaxSummary();
        $this->paymentModeOptions = trans('finance.payments.methods');
    }

    public function loadInvoice()
    {
        $this->invoice = Invoice::with([
            'details',
            'details.taxes.tax',
            'user',
            'payments'
        ])->findOrFail($this->invoiceId);
    }

    protected function calculateTaxSummary()
    {
        $taxSummary = [];
        
        foreach ($this->invoice->details as $detail) {
            foreach ($detail->taxes as $invoiceTax) {
                $tax = $invoiceTax->tax;
                $taxAmount = ($detail->amount * $tax->tax_percent) / 100;
                
                if (!isset($taxSummary[$tax->id])) {
                    $taxSummary[$tax->id] = [
                        'name' => $tax->tax_name,
                        'rate' => $tax->tax_percent,
                        'amount' => 0
                    ];
                }
                
                $taxSummary[$tax->id]['amount'] += $taxAmount;
            }
        }

        $this->taxSummary = array_values($taxSummary);
    }

    public function proceedToPayment()
    {
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->paymentAmount = 0;
        $this->paymentMode = 'cash';
    }

    public function savePayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0.01|max:' . $this->invoice->total_amount,
            'paymentMode' => 'required|in:' . implode(',', array_keys($this->paymentModeOptions)),
        ]);

        try {
            // Calculate total paid amount including this payment
            $totalPaid = $this->invoice->payments->sum('amount_paid') + $this->paymentAmount;
            
            // Determine payment status
            $paymentStatus = match(true) {
                $totalPaid >= $this->invoice->total_amount => 'completed',
                $this->paymentAmount > 0 => 'partially_paid',
                default => 'pending'
            };

            // Create payment record
            $payment = $this->invoice->payments()->create([
                'user_id' => $this->invoice->user_id,
                'amount_paid' => $this->paymentAmount,
                'payment_date' => now(),
                'status' => 'completed',
                'payment_mode' => $this->paymentMode,
            ]);

            // Update invoice status
            $this->invoice->status = match($paymentStatus) {
                'completed' => 'paid',
                'partially_paid' => 'partially_paid',
                default => 'unpaid'
            };
            $this->invoice->save();

            // Refresh invoice data
            $this->loadInvoice();

            // Close modal and show success message
            $this->closePaymentModal();
            $this->alert('success', __('finance.payments.recorded'));

        } catch (\Exception $e) {
            $this->alert('error', __('finance.payments.payment_failed') . ': ' . $e->getMessage());
        }
    }

    public function getDueAmountProperty()
    {
        return $this->invoice->total_amount - $this->invoice->payments->sum('amount_paid');
    }

    public function downloadPdf()
    {
        $pdf = PDF::loadView('pdf.invoice', [
            'invoice' => $this->invoice,
            'taxSummary' => $this->taxSummary
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'Invoice-' . $this->invoice->invoice_number . '.pdf');
    }

    public function sendInvoice()
    {
        try {
            // Show immediate success message
            $this->alert('success', __('finance.invoices.sent_to') . ' ' . $this->invoice->user->email);

            // Queue the email sending
            Mail::to($this->invoice->user->email)
                ->queue(new InvoiceMail($this->invoice, $this->taxSummary));

        } catch (\Exception $e) {
            \Log::error('Invoice email sending failed: ' . $e->getMessage());
            $this->alert('error', __('finance.invoices.send_failed'));
        }
    }

    public function viewPayment($paymentId)
    {
        return redirect()->route('payments.show', $paymentId);
    }

    public function render()
    {
        return view('livewire.invoice.view-invoice-details');
    }
}
