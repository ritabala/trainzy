<?php

namespace App\Livewire\Invoice;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class InvoiceTable extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $status = '';
    public $dateRangeStart;
    public $dateRangeEnd;
    public $perPage = 5;
    public $showPaymentModal = false;
    public $selectedInvoice;
    public $paymentAmount = 0;
    public $paymentMode = 'cash';
    public $paymentModeOptions = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'dateRangeStart' => ['except' => ''],
        'dateRangeEnd' => ['except' => ''],
    ];

    protected $rules = [
        'dateRangeStart' => 'nullable|date',
        'dateRangeEnd' => 'nullable|date|after_or_equal:dateRangeStart',
    ];

    // protected $messages = [
    //     'dateRangeEnd.after_or_equal' => __('finance.invoices.end_date_rule'),
    // ];

    protected $listeners = ['deleteInvoice'];

    public function mount() {
        $this->paymentModeOptions = trans('finance.payments.methods');
        $this->messages = [
            'dateRangeEnd.after_or_equal' => __('finance.invoices.end_date_rule'),
        ];
    }

    // public function getPaymentModeOptionsProperty()
    // {
    //     return trans('finance.payments.methods');
    // }

    public function updated($property)
    {
        if (in_array($property, ['search', 'status', 'dateRangeStart', 'dateRangeEnd'])) {
            $this->resetPage();
            
            // Validate date range when either date changes
            if (in_array($property, ['dateRangeStart', 'dateRangeEnd'])) {
                $this->validateOnly($property);
            }
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->dateRangeStart = '';
        $this->dateRangeEnd = '';
        $this->resetPage();
        $this->resetValidation();
    }

    public function handleDeleteInvoice($id)
    {
        $this->alert('warning', __('finance.invoices.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'deleteInvoice',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('common.yes_delete'),
            'data' => [
                'invoice_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteInvoice($data)
    {
        try {
            $invoice = Invoice::findOrFail($data['invoice_id']);
            $invoice->delete();
            $this->alert('success', __('finance.invoices.deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('finance.invoices.delete_failed'));
        }
    }

    public function editInvoice($invoiceId)
    {
        return redirect()->route('invoices.edit', ['invoice' => $invoiceId]);
    }

    public function makePayment($invoiceId)
    {
        $this->selectedInvoice = Invoice::findOrFail($invoiceId);
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->paymentAmount = 0;
        $this->paymentMode = 'cash';
        $this->selectedInvoice = null;
    }

    public function savePayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0.01|max:' . $this->selectedInvoice->total_amount,
            'paymentMode' => 'required|in:' . implode(',', array_keys($this->paymentModeOptions)),
        ]);

        try {
            // Calculate total paid amount including this payment
            $totalPaid = $this->selectedInvoice->payments->sum('amount_paid') + $this->paymentAmount;
            
            // Determine payment status
            $paymentStatus = match(true) {
                $totalPaid >= $this->selectedInvoice->total_amount => 'completed',
                $this->paymentAmount > 0 => 'partially_paid',
                default => 'pending'
            };

            // Create payment record
            $payment = $this->selectedInvoice->payments()->create([
                'user_id' => $this->selectedInvoice->user_id,
                'amount_paid' => $this->paymentAmount,
                'payment_date' => now(),
                'status' => 'completed',
                'payment_mode' => $this->paymentMode,
            ]);

            // Update invoice status
            $this->selectedInvoice->status = match($paymentStatus) {
                'completed' => 'paid',
                'partially_paid' => 'partially_paid',
                default => 'unpaid'
            };
            $this->selectedInvoice->save();

            // Close modal and show success message
            $this->closePaymentModal();
            $this->alert('success', __('finance.payments.recorded'));

        } catch (\Exception $e) {
            $this->alert('error', __('finance.payments.payment_failed') . ': ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Invoice::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('invoice_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->dateRangeStart && $this->dateRangeEnd, function ($query) {
                $query->whereBetween('invoice_date', [
                    Carbon::parse($this->dateRangeStart)->startOfDay(),
                    Carbon::parse($this->dateRangeEnd)->endOfDay()
                ]);
            })->latest();

        // Get the latest invoice ID
        $latestInvoiceId = Invoice::latest('created_at')->value('id');

        return view('livewire.invoice.invoice-table', [
            'invoices' => $query->paginate($this->perPage),
            'latestInvoiceId' => $latestInvoiceId
        ]);
    }
}
