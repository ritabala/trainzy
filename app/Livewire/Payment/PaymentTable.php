<?php

namespace App\Livewire\Payment;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PaymentTable extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $status = '';
    public $paymentMode = '';
    public $paymentModeOptions = [];
    public $paidOnDate = '';
    public $perPage = 5;
    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'paymentMode' => ['except' => ''],
        'paidOnDate' => ['except' => ''],
    ];

    protected $listeners = ['deletePayment'];


    public function mount() {
        $this->paymentModeOptions = trans('finance.payments.methods');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'status', 'paymentMode', 'paidOnDate']);
        $this->resetPage();
    }

    public function viewPayment($paymentId)
    {
        return redirect()->route('payments.show', $paymentId);
    }

    public function editPayment($paymentId)
    {
        return redirect()->route('payments.edit', $paymentId);
    }

    public function downloadPayment($paymentId)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('download_payment_receipt'), 403);
        $payment = Payment::with(['user', 'invoice'])->findOrFail($paymentId);
        
        $pdf = PDF::loadView('pdf.payment-receipt', [
            'payment' => $payment,
            'user' => $payment->user,
            'invoice' => $payment->invoice
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, "payment-receipt-{$payment->id}.pdf");
    }

    public function handleDeletePayment($id)
    {
        $this->alert('warning', __('finance.payments.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'deletePayment',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('common.yes_delete'),
            'data' => [
                'payment_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deletePayment($data)
    {
        try {
            DB::beginTransaction();

            $payment = Payment::with('invoice')->findOrFail($data['payment_id']);
            $invoice = $payment->invoice;

            // Delete the payment
            $payment->delete();

            // Update invoice status if it exists
            if ($invoice) {
                $totalPaid = $invoice->payments()
                    ->whereIn('status', ['Completed', 'Partially Paid'])
                    ->sum('amount_paid');

                if ($totalPaid >= $invoice->total_amount) {
                    $invoice->status = 'Paid';
                } elseif ($totalPaid > 0) {
                    $invoice->status = 'Partially Paid';
                } else {
                    $invoice->status = 'Unpaid';
                }
                $invoice->save();
            }

            DB::commit();
            $this->paymentToDelete = null;
            $this->alert('success', __('finance.payments.deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('finance.payments.delete_failed'));
        }
    }

    public function cancelDelete()
    {
        $this->paymentToDelete = null;
    }

    public function render()
    {
        $query = Payment::with(['user', 'invoice']);

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('invoice', function($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Apply status filter
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Apply payment mode filter
        if ($this->paymentMode) {
            $query->where('payment_mode', $this->paymentMode);
        }

        // Apply paid on date filter
        if ($this->paidOnDate) {
            $query->whereDate('payment_date', $this->paidOnDate);
        }

        $payments = $query->latest()->paginate($this->perPage);

        return view('livewire.payment.payment-table', [
            'payments' => $payments
        ]);
    }
}
