<?php

namespace App\Livewire\Invoice;
use App\Models\User;
use App\Models\UserMembership;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceDetailTax;
use App\Models\MembershipFrequency;
use App\Models\Product;
use App\Models\ProductTax;
use App\Models\Tax;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class CreateEditInvoice extends Component
{
    public $userId;
    public $selectedUserId;
    public $member;
    public $userMembershipId;
    public $userMembership;
    public $invoice = [];
    public $invoiceDetails = [];
    public $membershipFrequencies = [];
    public $products = [];
    public $taxes = [];
    public $totalTaxAmount = 0;
    public $taxSummary = [];
    public $editMode = false;
    public $invoiceId;
    public $fromAdd = false;
    public $productSearchQuery = '';
    public $paymentReceived = false;
    public $paymentAmount = 0;
    public $paymentMode = 'cash';
    public $paymentStatus = 'Completed';
    public $users = [];
    public $paymentModeOptions = [];
    public $userSearchQuery = '';
    public $perPage = 5;
    public $userPage = 1;
    public $selectedProductId ="";

    // Status mapping between payment and invoice statuses for payment gateway integration
    private const STATUS_MAPPING = [
        'pending' => 'unpaid',
        'completed' => 'paid',
        'failed' => 'unpaid',
        'partially_paid' => 'partially_paid',
        'cancelled' => 'cancelled',
        'refunded' => 'cancelled',
        'paid' => 'paid',
        'unpaid' => 'unpaid'
    ];

    // Flag to determine if payment is from gateway
    public $isGatewayPayment = false;
    
    public function rules()
    {
        $rules = [
            'invoice.invoice_number' => 'required|string|max:20',
            'invoice.invoice_date' => 'required|date',
            'invoice.due_date' => 'required|date|after_or_equal:invoice.invoice_date',
            'invoice.discount_type' => 'required|in:%,fixed',
            'invoice.discount_value' => 'required|numeric|min:0',
            'invoiceDetails.*.name' => 'required|string',
            'invoiceDetails.*.unit_price' => 'required|numeric|min:0',
        ];

        // Only add selectedUserId validation if fromAdd is true
        if ($this->fromAdd) {
            $rules['selectedUserId'] = 'required|exists:users,id';
            $rules['invoiceDetails.*.quantity'] = 'required|integer|min:1';
        }

        return $rules;
    }

    protected function messages(): array
    {
        return __('finance.invoices.validation_messages');
    }

    public function mount($userId = null, $invoiceId = null, $fromAdd = false)
    {
        $this->fromAdd = $fromAdd;
        $this->paymentModeOptions = trans('finance.payments.methods');
        $this->membershipFrequencies = MembershipFrequency::with(['membership', 'frequency'])->get();
        $this->products = Product::with(['invoiceDetails'])->get();
        $this->taxes = Tax::all();
        
        // Only load users if fromAdd is true
        if ($this->fromAdd) {
            $this->loadUsers();
        }

        if ($invoiceId) {
            $this->editMode = true;
            $this->invoiceId = $invoiceId;
            $this->loadInvoice();
        } else {
            // Initialize invoice with default values only for new invoices
            $this->initializeInvoice();
            
            // If userId is provided and not fromAdd, initialize with that user
            if ($userId && !$this->fromAdd) {
                $this->initFromUserId($userId);
            }
        }
    }

    public function loadUsers()
    {
        $query = User::query()
            ->whereHas('roles', function($query) {
                $query->where('name', 'member-' . gym()->id);
            })
            ->when($this->userSearchQuery, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->userSearchQuery . '%')
                      ->orWhere('email', 'like', '%' . $this->userSearchQuery . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->skip(($this->userPage - 1) * $this->perPage)
            ->limit($this->perPage);

        $newUsers = $query->get();
        
        if ($this->userPage === 1) {
            $this->users = $newUsers;
        } else {
            $this->users = $this->users->concat($newUsers);
        }
    }

    public function updatedUserSearchQuery()
    {
        $this->userPage = 1;
        $this->loadUsers();
    }

    public function loadMoreUsers()
    {
        $this->userPage++;
        $this->loadUsers();
    }

    public function updatedSelectedUserId($value)
    {
        if ($this->fromAdd && $value) {
            // Reset invoice details
            $this->invoiceDetails = [];
            
            // Initialize with the new user
            $this->initFromUserId($value);
            
            // Calculate totals after updating details
            $this->calculateTotals();
        } else {
            $this->resetUserData();
        }
    }

    protected function resetUserData()
    {
        $this->userId = null;
        $this->member = null;
        $this->userMembership = null;
        $this->invoiceDetails = [];
        $this->initializeInvoice();
        $this->addItem();
    }

    protected function initFromUserId($userId)
    {
        $this->userId = $userId;
        $this->member = User::with([
            'latestMembership.membership', 
            'latestMembership.membershipFrequency', 
            'latestMembership.membershipFrequency.frequency'
        ])->findOrFail($this->userId);
        
        // Get the user's latest membership if exists and is active
        $this->userMembership = $this->member->latestMembership && $this->member->latestMembership->membership_status === 'active' 
            ? $this->member->latestMembership 
            : null;

        // Update invoice user_id
        $this->invoice['user_id'] = $this->userId;
        
        // Only add membership item if not adding directly and user has active membership
        if (!$this->fromAdd && $this->userMembership) {
            $this->addMembershipItem();
        }
    }

    protected function initializeInvoice()
    {
        $prefix = $this->editMode ? 'INV#' : 'INV-';
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();
        $nextNumber = $lastInvoice ? $lastInvoice->id + 1 : 1;
        
        // Add leading zeros only for numbers up to 100
        $invoiceNumber = $nextNumber <= 100 
            ? str_pad($nextNumber, 3, '0', STR_PAD_LEFT)
            : (string)$nextNumber;

        $this->invoice = [
            'user_id' => $this->userId,
            'invoice_date' => Carbon::now()->format('Y-m-d'),
            'due_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'invoice_prefix' => $prefix,
            'invoice_number' => $invoiceNumber,
            'status' => 'unpaid',
            'sub_total' => 0.00,
            'discount_type' => '%',
            'discount_value' => 0,
            'discount_amount' => 0.00,
            'total_amount' => 0.00,
            'notes' => '',
            'tax_amount' => 0.00
        ];

        // Initialize other required properties
        $this->totalTaxAmount = 0.00;
        $this->taxSummary = [];
        $this->invoiceDetails = [];
    }

    public function calculateTotals()
    {
        // Calculate subtotal using collection
        $this->invoice['sub_total'] = collect($this->invoiceDetails)->sum('amount');
        
        // Calculate discount
        $discountAmount = $this->calculateDiscountAmount();
        $this->invoice['discount_amount'] = $discountAmount;
        $discountedTotal = max(0, $this->invoice['sub_total'] - $discountAmount);
        
        // Reset tax summary and total
        $this->taxSummary = [];
        $this->totalTaxAmount = 0;
        
        // Calculate taxes for each item
        foreach ($this->invoiceDetails as $index => $item) {
            $discountedAmount = $this->getDiscountedItemAmount($item['amount']);
            $itemTaxAmount = 0;
            
            if (!empty($item['selected_taxes'])) {
                foreach ($item['selected_taxes'] as $taxId) {
                    $tax = $this->taxes->find($taxId);
                    if ($tax) {
                        $singleTaxAmount = ($discountedAmount * $tax->tax_percent) / 100;
                        $itemTaxAmount += $singleTaxAmount;
                        
                        // Initialize tax summary entry if not exists
                        if (!isset($this->taxSummary[$taxId])) {
                            $this->taxSummary[$taxId] = [
                                'name' => $tax->tax_name,
                                'rate' => $tax->tax_percent,
                                'amount' => 0
                            ];
                        }
                        
                        // Add to tax summary
                        $this->taxSummary[$taxId]['amount'] += $singleTaxAmount;
                    }
                }
            }
            
            // Update item tax amount
            $this->invoiceDetails[$index]['tax_amount'] = $itemTaxAmount;
            $this->totalTaxAmount += $itemTaxAmount;
        }
        
        // Calculate final total
        $this->invoice['total_amount'] = $discountedTotal + $this->totalTaxAmount;
    }

    protected function addMembershipItem()
    {
        if ($this->userMembership && $this->userMembership->membershipFrequency) {
            $membershipFrequency = $this->userMembership->membershipFrequency;
            $membership = $this->userMembership->membership;
            $frequency = $membershipFrequency->frequency;
            
            $this->invoiceDetails[] = [
                'type' => 'membership',
                'user_membership_id' => $this->userMembership->id,
                'membership_frequency_id' => $membershipFrequency->id,
                'product_id' => null,
                'name' => $membership->name . ' (' . $frequency->name . ')',
                'description' => "Services included in membership: " . $membership->services->pluck('name')->implode(', '),
                'quantity' => 1,
                'unit_price' => $membershipFrequency->price,
                'amount' => $membershipFrequency->price,
                'tax_amount' => 0,
                'selected_taxes' => []
            ];
            
            $this->calculateTotals();
        }
    }

    protected function loadInvoice()
    {
        $invoice = Invoice::with(['details', 'details.taxes'])->findOrFail($this->invoiceId);
        $this->userId = $invoice->user_id;
        $this->member = User::find($this->userId);
        $this->userMembership = UserMembership::find($invoice->user_membership_id);

        $this->invoice = [
            'user_id' => $invoice->user_id,
            'invoice_date' => $invoice->invoice_date->format('Y-m-d'),
            'due_date' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : Carbon::parse($invoice->invoice_date)->addDays(7)->format('Y-m-d'),
            'invoice_prefix' => $invoice->invoice_prefix,
            'invoice_number' => $invoice->invoice_number,
            'status' => $invoice->status,
            'sub_total' => $invoice->sub_total,
            'discount_type' => $invoice->discount_type,
            'discount_value' => $invoice->discount_value,
            'discount_amount' => $invoice->discount_amount,
            'total_amount' => $invoice->total_amount,
            'notes' => $invoice->notes,
        ];

        $this->invoiceDetails = $invoice->details->map(function ($detail) {
            $type = $detail->membership_frequency_id ? 'membership' : ($detail->product_id ? 'product' : 'custom');
            
            // Get the tax IDs for this detail
            $selectedTaxes = $detail->taxes->pluck('tax_id')->toArray();
            
            return [
                'id' => $detail->id,
                'type' => $type,
                'user_membership_id' => $detail->user_membership_id,
                'membership_frequency_id' => $detail->membership_frequency_id,
                'product_id' => $detail->product_id,
                'name' => $detail->name ?? '',
                'description' => $detail->description,
                'quantity' => $detail->quantity,
                'unit_price' => $detail->unit_price,
                'amount' => $detail->amount,
                'tax_amount' => $this->calculateItemTaxAmount($detail),
                'selected_taxes' => $selectedTaxes
            ];
        })->toArray();

        if (empty($this->invoiceDetails)) {
            $this->addItem();
        }
        
        $this->calculateTotals();
    }

    protected function calculateItemTaxAmount($detail)
    {
        return $detail->taxes->sum(function ($detailTax) use ($detail) {
            $tax = $this->taxes->find($detailTax->tax_id);
            return $tax ? ($detail->amount * $tax->tax_percent) / 100 : 0;
        });
    }

    public function addItem()
    {
        $this->invoiceDetails[] = [
            'type' => 'custom',
            'user_membership_id' => null,
            'membership_frequency_id' => null,
            'user_membership_id' => null,
            'product_id' => null,
            'name' => '',
            'description' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'amount' => 0,
            'tax_amount' => 0,
            'selected_taxes' => []
        ];
    }

    public function removeItem($index)
    {
        if (isset($this->invoiceDetails[$index])) {
            unset($this->invoiceDetails[$index]);
            $this->invoiceDetails = array_values($this->invoiceDetails);
            $this->calculateTotals();
        }
    }

    protected function calculateDiscountAmount()
    {
        $subTotal = $this->invoice['sub_total'];
        $discountType = $this->invoice['discount_type'];
        
        // Set discount value to 0 if empty/null
        $this->invoice['discount_value'] = !empty($this->invoice['discount_value']) ? (float)$this->invoice['discount_value'] : 0;
        $discountValue = $this->invoice['discount_value'];
        
        return $discountType === '%' ? ($subTotal * $discountValue) / 100 : $discountValue;
    }

    protected function getDiscountedItemAmount($amount)
    {
        $subTotal = $this->invoice['sub_total'] ?? 0;
        if ($subTotal <= 0) {
            return $amount;
        }
        
        $discountAmount = $this->calculateDiscountAmount();
        $discountRatio = 1 - ($discountAmount / $subTotal);
        
        return $amount * max(0, $discountRatio);
    }

    public function calculateDiscount()
    {
        $this->calculateTotals();
    }

    public function calculateAmount($index)
    {
        // Set quantity and unit price to 0 if empty/null
        $this->invoiceDetails[$index]['quantity'] = !empty($this->invoiceDetails[$index]['quantity']) ? (int)$this->invoiceDetails[$index]['quantity'] : 0;
        $this->invoiceDetails[$index]['unit_price'] = !empty($this->invoiceDetails[$index]['unit_price']) ? (float)$this->invoiceDetails[$index]['unit_price'] : 0;
        
        // Calculate amount
        $this->invoiceDetails[$index]['amount'] = $this->invoiceDetails[$index]['quantity'] * $this->invoiceDetails[$index]['unit_price'];
        $this->calculateTotals();
    }

    public function toggleTax($index, $taxId)
    {
        $selectedTaxes = $this->invoiceDetails[$index]['selected_taxes'] ?? [];
        
        if (in_array($taxId, $selectedTaxes)) {
            $this->invoiceDetails[$index]['selected_taxes'] = array_diff($selectedTaxes, [$taxId]);
        } else {
            $this->invoiceDetails[$index]['selected_taxes'][] = $taxId;
        }

        $this->calculateAmount($index);
    }

    public function updateDiscountType($type)
    {
        $this->invoice['discount_type'] = $type;
        $this->calculateDiscount();
    }

    public function addProduct($productId)
    {
        if ($productId) {
            $this->selectedProductId = $productId;
            $product = $this->products->find($productId);
            if ($product) {
                $productTaxes = ProductTax::where('product_id', $product->id)->pluck('tax_id')->toArray();
                $this->invoiceDetails[] = [
                    'type' => 'product',
                    'user_membership_id' => null,
                    'membership_frequency_id' => null,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description ?? '',
                    'quantity' => 1,
                    'unit_price' => $product->price,
                    'amount' => $product->price,
                    'tax_amount' => 0,
                    'selected_taxes' => $productTaxes,
                ];
                $this->calculateAmount(count($this->invoiceDetails) - 1);
            }
        }
    }

    public function togglePaymentReceived()
    {
        $this->paymentReceived = !$this->paymentReceived;
        
        if (!$this->paymentReceived) {
            $this->paymentAmount = 0;
            $this->paymentMode = 'cash';
        }
    }

    private function saveInvoice()
    {
        $invoice = $this->editMode 
            ? Invoice::findOrFail($this->invoiceId)
            : new Invoice();

        $invoice->fill([
            'user_id' => $this->userId ?? $this->selectedUserId,
            'user_membership_id' => $this->fromAdd ? null : $this->userMembership?->id,
            'invoice_date' => $this->invoice['invoice_date'],
            'due_date' => $this->invoice['due_date'],
            'invoice_prefix' => $this->invoice['invoice_prefix'],
            'invoice_number' => $this->invoice['invoice_number'],
            'status' => $this->invoice['status'] ?? 'unpaid',
            'sub_total' => $this->invoice['sub_total'],
            'discount_type' => $this->invoice['discount_type'],
            'discount_value' => $this->invoice['discount_value'],
            'discount_amount' => $this->invoice['discount_amount'],
            'total_amount' => $this->invoice['total_amount'],
            'notes' => $this->invoice['notes'],
        ]);

        $invoice->saveOrFail();
        
        return $invoice;
    }

    private function saveInvoiceDetails($invoice)
    {
        // Delete existing details if in edit mode
        if ($this->editMode) {
            $existingIds = collect($this->invoiceDetails)->pluck('id')->filter()->toArray();
            InvoiceDetail::where('invoice_id', $this->invoiceId)
                ->whereNotIn('id', $existingIds)
                ->delete();
        }

        // Save all details and collect tax data
        foreach ($this->invoiceDetails as $item) {
            $detail = $this->editMode && isset($item['id'])
                ? InvoiceDetail::findOrFail($item['id'])
                : new InvoiceDetail();

            $detail->fill([
                'invoice_id' => $invoice->id,
                'user_membership_id' => $item['type'] === 'membership' ? $item['user_membership_id'] : null,
                'membership_frequency_id' => $item['type'] === 'membership' ? $item['membership_frequency_id'] : null,
                'product_id' => $item['type'] === 'product' ? $item['product_id'] : null,
                'name' => $item['name'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'amount' => $item['amount'],
            ]);

            $detail->saveOrFail();

            // Delete existing taxes in edit mode
            if ($this->editMode) {
                InvoiceDetailTax::where('invoice_detail_id', $detail->id)->delete();
            }

            // Save selected taxes for all item types
            if (!empty($item['selected_taxes'])) {
                foreach ($item['selected_taxes'] as $taxId) {
                    InvoiceDetailTax::create([
                        'invoice_detail_id' => $detail->id,
                        'tax_id' => $taxId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        return $invoice;
    }

    private function processPayment($invoice)
    {
        if (!$this->paymentReceived || $this->paymentAmount <= 0) {
            return;
        }

        // Determine payment status based on payment type
        $paymentStatus = $this->isGatewayPayment 
            ? 'Pending' // Initial status for gateway payments, will be updated by gateway response
            : 'Completed'; // Direct payments are always completed

        // Create payment record
        $payment = Payment::create([
            'user_id' => $this->userId,
            'invoice_id' => $invoice->id,
            'amount_paid' => $this->paymentAmount,
            'payment_date' => Carbon::now(),
            'status' => $paymentStatus,
            'payment_mode' => $this->paymentMode,
        ]);

        if ($this->isGatewayPayment) {
            // Case 2: Payment Gateway Integration
            // Payment status will be updated based on gateway response
            // Invoice status will be mapped from payment status
            $invoice->status = self::STATUS_MAPPING[$payment->status] ?? 'unpaid';
        } else {
            // Case 1: Direct Payment Recording
            // Calculate invoice status based on payment amount
            $invoice->status = match(true) {
                $this->paymentAmount >= $this->invoice['total_amount'] => 'paid',
                $this->paymentAmount > 0 => 'partially_paid',
                default => 'unpaid'
            };
        }

        $invoice->saveOrFail();
    }

    public function save()
    {
        $this->validate();

        try {
            $invoice = DB::transaction(function () {
                // 1. Save the main invoice
                $invoice = $this->saveInvoice();
                
                // 2. Save invoice details and their taxes
                $this->saveInvoiceDetails($invoice);
                
                // 3. Process payment if received
                $this->processPayment($invoice);
                
                return $invoice;
            });

            session()->flash('message', $this->editMode ? __('finance.invoices.updated') : __('finance.invoices.created'));
            return redirect()->route('invoices.show', $invoice->id);
            
        } catch (\Throwable $e) {
            session()->flash('error', __('finance.invoices.save_error') . $e->getMessage());
            return null;
        }
    }

    public function cancel()
    {
        return redirect()->route('invoices.index');
    }

    public function getFilteredProductsProperty()
    {
        if (empty($this->productSearchQuery)) {
            return $this->products;
        }

        $searchTerm = strtolower($this->productSearchQuery);
        return $this->products->filter(function ($product) use ($searchTerm) {
            return str_contains(strtolower($product->name), $searchTerm);
        });
    }

    public function render()
    {
        return view('livewire.invoice.create-edit-invoice', [
            'filteredProducts' => $this->getFilteredProductsProperty(),
            'fromAdd' => $this->fromAdd,
            'hasMoreUsers' => User::when($this->userSearchQuery, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->userSearchQuery . '%')
                      ->orWhere('email', 'like', '%' . $this->userSearchQuery . '%');
                });
            })->count() > ($this->userPage * $this->perPage)
        ]);
    }
}
