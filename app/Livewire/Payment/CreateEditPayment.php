<?php

namespace App\Livewire\Payment;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class CreateEditPayment extends Component
{
    public $selectedUserId = null;

    public $selectedInvoiceId = null;

    public float $amount_paid;

    public string $payment_date = '';

    public $payment_mode = '';

    public $transaction_no = null;

    protected function getPaymentRules()
    {
        return [
            'payment_date' => 'required|date|before:tomorrow',
            'payment_mode' => 'required|in:' . implode(',', array_keys($this->paymentModeOptions)),
            'transaction_no' => $this->isEditMode
                ? ['nullable', 'string', 'max:255', Rule::unique('payments', 'transaction_no')->ignore($this->paymentId)]
                : ['nullable', 'string', 'max:255', Rule::unique('payments', 'transaction_no')],
            'amount_paid' => 'required|numeric|min:0.01',
        ];
    }

    protected function getPaymentMessages()
    {
        return [
            'transaction_no.unique' => __('finance.payments.transaction_no.unique'),
            'transaction_no.nullable' => __('finance.payments.transaction_no.nullable'),
            'transaction_no.string' => __('finance.payments.transaction_no.string'),
            'transaction_no.max' => __('finance.payments.transaction_no.max'), 
            'amount_paid.required' => __('finance.payments.amount_paid.required'),
            'amount_paid.numeric' => __('finance.payments.amount_paid.numeric'),
            'amount_paid.min' => __('finance.payments.amount_paid.min'),
            'payment_date.required' => __('finance.payments.payment_date.required'),
            'payment_date.datetime' => __('finance.payments.payment_date.datetime'),
            'payment_date.before' => __('finance.payments.payment_date.before'),
            'payment_mode.required' => __('finance.payments.payment_mode.required'),
            'payment_mode.in' => __('finance.payments.payment_mode.in'),
        ];
    }

    public $remarks = '';

    public $selectedInvoice = null;
    public $invoices = [];
    public $users = [];
    public $paymentModeOptions = [];
    public $userSearchQuery = '';
    public $hasMoreUsers = false;
    public $perPage = 10;
    public $userPage = 1;

    public $paymentId = null;
    public $isEditMode = false;

    public function mount($paymentId = null)
    {
        $this->loadUsers();
        $this->loadInvoices();
        $this->paymentModeOptions = trans('finance.payments.methods');

        if ($paymentId) {
            $this->paymentId = $paymentId;
            $this->isEditMode = true;
            $this->loadPaymentData();
        }
    }

    public function loadUsers($loadMore = false)
    {
        if (!$loadMore) {
            $this->userPage = 1;
            $this->users = [];
        }

        $query = User::query()
            ->whereHas('roles', function($query) {
                $query->where('name', 'member-' . gym()->id);
            })
            ->orderBy('name');

        if ($this->userSearchQuery) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->userSearchQuery . '%')
                    ->orWhere('email', 'like', '%' . $this->userSearchQuery . '%');
            });
        }

        $newUsers = $query->skip(($this->userPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })
            ->toArray();

        $this->users = $loadMore ? array_merge($this->users, $newUsers) : $newUsers;
        $this->hasMoreUsers = count($newUsers) === $this->perPage;
    }

    public function updatedUserSearchQuery()
    {
        $this->loadUsers();
    }

    public function loadMoreUsers()
    {
        if ($this->hasMoreUsers) {
            $this->userPage++;
            $this->loadUsers(true);
        }
    }

    public function loadInvoices($userId = null)
    {
        $query = Invoice::query()
            ->whereIn('status', ['unpaid', 'partially_paid']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $this->invoices = $query->orderBy('invoice_number', 'desc')
            ->get()
            ->map(function ($invoice) {
                $paidAmount = $invoice->payments->sum('amount_paid');
                $dueAmount = $invoice->total_amount - $paidAmount;
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => $invoice->total_amount,
                    'due_amount' => $dueAmount,
                    'user_id' => $invoice->user_id,
                ];
            })
            ->filter(function ($invoice) {
                return $invoice['due_amount'] > 0;
            })
            ->values()
            ->toArray();
    }

    public function updatedSelectedUserId($value)
    {
        $this->selectedInvoiceId = null;
        $this->selectedInvoice = null;
        $this->amount_paid = 0.0;
        $this->loadInvoices($value);
    }

    public function updatedSelectedInvoiceId($value)
    {
        if ($value) {
            $invoice = collect($this->invoices)->firstWhere('id', $value);
            if ($invoice) {
                $this->selectedInvoice = Invoice::find($invoice['id']);
                $this->amount_paid = (float) $invoice['due_amount'];
                $this->selectedUserId = $invoice['user_id'];
                $this->loadInvoices($invoice['user_id']);
            }
        } else {
            $this->selectedInvoice = null;
            $this->amount_paid = 0.0;
            $this->selectedUserId = null;
            $this->loadInvoices();
        }
    }

    public function loadPaymentData()
    {
        $payment = Payment::with(['invoice', 'user'])->findOrFail($this->paymentId);
        
        $this->selectedUserId = $payment->user_id;
        $this->selectedInvoiceId = $payment->invoice_id;
        $this->amount_paid = (float) $payment->amount_paid;
        $this->payment_date = $payment->payment_date->format('Y-m-d H:i');
        $this->payment_mode = $payment->payment_mode;
        $this->transaction_no = $payment->transaction_no;
        $this->remarks = $payment->remarks;

        if ($payment->invoice) {
            $this->selectedInvoice = $payment->invoice;
            $this->loadInvoices($payment->user_id);
        }
    }

    public function save()
    {
        
        try {
            // Clear any previous errors
            $this->resetErrorBag();
            
            // Validate the form
            $this->validate($this->getPaymentRules(), $this->getPaymentMessages());

            DB::beginTransaction();

            $paymentData = [
                'user_id' => $this->selectedUserId,
                'invoice_id' => $this->selectedInvoiceId,
                'amount_paid' => (float) $this->amount_paid,
                'payment_date' => \Carbon\Carbon::parse($this->payment_date)->format('Y-m-d H:i'),
                'payment_mode' => $this->payment_mode,
                'transaction_no' => $this->transaction_no ?: null,
                'status' => 'completed',
                'remarks' => $this->remarks
            ];

            if ($this->isEditMode) {
                $payment = Payment::findOrFail($this->paymentId);
                $oldAmount = $payment->amount_paid;
                $payment->update($paymentData);
            } else {
                $payment = Payment::create($paymentData);
            }

            if ($this->selectedInvoiceId) {
                $invoice = Invoice::findOrFail($this->selectedInvoiceId);

                // Calculate new paid amount from all payments
                $newPaidAmount = $invoice->payments->sum('amount_paid');
                $newDueAmount = $invoice->total_amount - $newPaidAmount;

                // Update invoice status
                $invoice->update([
                    'status' => $newDueAmount <= 0 ? 'paid' : 'partially_paid'
                ]);
            }

            DB::commit();
            session()->flash('success', $this->isEditMode ? __('finance.payments.updated') : __('finance.payments.recorded'));
            return redirect()->route('payments.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error(__('errors.validation_failed') . json_encode($e->errors()));
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) { // Duplicate entry
                $this->addError('transaction_no', __('finance.payments.transaction_no.unique'));
            } else {
                $action = $this->isEditMode ? __('finance.payments.actions.update') : __('finance.payments.actions.create');
                $message = $e->getMessage();
                \Log::error(__('finance.payments.log.payment_failed', ['action' => $action, 'message' => $message]));
                $this->addError('general', __('finance.payments.log.payment_failed', ['action' => $action, 'message' => $message]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $action = $this->isEditMode ? __('finance.payments.actions.update') : __('finance.payments.actions.create');
            $message = $e->getMessage();
            \Log::error(__('finance.payments.log.payment_failed', ['action' => $action,'message' => $message]));
            $this->addError('general', __('finance.payments.log.payment_failed', ['action' => $action, 'message' => $message]));
        }
    }

    public function render()
    {
        return view('livewire.payment.create-edit-payment');
    }
}
