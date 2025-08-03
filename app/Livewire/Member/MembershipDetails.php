<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\User;
use App\Models\UserMembership;
use App\Models\Membership;
use App\Models\MembershipFrequency;
use App\Models\MembershipService;
use App\Models\Service;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceDetailTax;

class MembershipDetails extends Component
{
    use LivewireAlert;

    public $user;
    public $latestMembership;
    public $upcomingRenewedMembership;
    public $showModal = false;
    public $modalAction = ''; // 'edit', 'renew', or 'assign'
    
    // Form fields
    public $selectedMembershipId;
    public $selectedFrequencyId;
    public $membershipStartDate;
    public $membershipEndDate;
    public $autoRenewal;
    public $lastRenewalDate;
    public $nextRenewalDate;
    
    // Data for dropdowns
    public $availableMemberships = [];
    public $availableFrequencies = [];
    public $historicalMemberships;
    public $showRenewalFields = false;
    public $showRenewButton = true;
    public $showEditButton = false;
    public $showGenerateInvoiceButton = false;
    public $showAssignButton = false;
    public $showStopAutoRenewalButton = false;

    protected $listeners = ['stopAutoRenewal', 'handleConfirmRenew', 'handleRemoveUpcomingRenewedMembership'];

    protected function rules()
    {
        $rules = [
            'membershipStartDate' => 'required|date',
            'autoRenewal' => 'boolean',
        ];

        if ($this->modalAction === 'assign' || $this->modalAction === 'renew' || $this->modalAction === 'edit') {
            $rules['membershipEndDate'] = [
                'required',
                'date',
                'after:membershipStartDate',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->endOfDay()->isPast()) {
                        $fail(__('members.expired_membership'));
                    }
                }
            ];
        }

        if ($this->modalAction === 'assign' || $this->modalAction === 'renew') {
            $rules['selectedMembershipId'] = 'required|exists:memberships,id';
            $rules['selectedFrequencyId'] = 'required|exists:membership_frequencies,id';
        }

        if ($this->modalAction === 'renew') {
            $rules['membershipStartDate'] = [
                'required',
                'date',
                'after:today',
                function ($attribute, $value, $fail) {
                    if ($this->latestMembership && Carbon::parse($value)->startOfDay()->lte(Carbon::parse($this->latestMembership->membership_expiry_date)->startOfDay())) {
                        $fail(__('members.start_date_rule'));
                    }
                }
            ];
            if ($this->autoRenewal) {
                $rules['nextRenewalDate'] = 'required|date|after:membershipStartDate';
            }
            $rules['lastRenewalDate'] = 'nullable|date';
        }

        return $rules;
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadMembershipData();
        $this->loadAvailableMemberships();
    }

    public function loadMembershipData()
    {
        $memberships = UserMembership::with(UserMembership::defaultEagerLoad())
            ->where('user_id', $this->user->id)
            ->orderByDesc('membership_start_date')
            ->get();

        $this->latestMembership = $memberships->firstWhere('membership_status', 'active') ?? $memberships->first();

        $this->upcomingRenewedMembership = $memberships->filter(fn($membership) => $membership->membership_status === 'upcoming' && $membership->id !== $this->latestMembership?->id)->first();

        $this->historicalMemberships = $memberships->reject(fn($membership) => $membership->id === $this->latestMembership?->id || $membership->membership_status === 'upcoming');

        $upcomingMembership = $memberships->filter(fn($membership) => 
        $membership->membership_status === 'upcoming' && $membership->id !== $this->latestMembership?->id)->first();
        
        $this->showRenewButton = match (true) {
            !isset($this->latestMembership) => false, // Hide if no latest membership exists
            $this->latestMembership->membership_status === 'active' && ($upcomingMembership || $this->latestMembership->auto_renewal) => false,
            $this->latestMembership->membership_status === 'active' && !$upcomingMembership && $this->latestMembership->membership_expiry_date->diffInDays(now()) <= 7 => true,
            $this->latestMembership->membership_status === 'expired' => true,
            default => $this->showRenewButton, // Retains previous value if none of the conditions match
        };

        if (isset($this->latestMembership) && $this->latestMembership->membership_status === 'active' && $this->latestMembership->auto_renewal) {
            $this->showStopAutoRenewalButton = true;
        }
        
        $this->showEditButton = isset($this->latestMembership) && $this->latestMembership->membership_status === 'active';
        $this->showAssignButton = !isset($this->latestMembership) || $this->latestMembership->membership_status === 'cancelled';  
        
        $this->showGenerateInvoiceButton = false;
        if (isset($this->latestMembership) && $this->latestMembership->membership_status === 'active') {
            $existingInvoice = Invoice::where('user_id', $this->user->id)
                ->where('user_membership_id', $this->latestMembership->id)
                ->exists();
            
            $this->showGenerateInvoiceButton = !$existingInvoice;
        }
    }

    public function loadAvailableMemberships()
    {
        $this->availableMemberships = Membership::where('is_active', true)->get();
    }

    public function updatedSelectedMembershipId($value)
    {
        // Reset frequency-related fields
        $this->selectedFrequencyId = null;
        $this->membershipStartDate = null;
        $this->membershipEndDate = null;
        
        // Load frequencies for the selected membership
        $this->loadFrequenciesForMembership($value);
        
        // Set default start date to today for new assignments
        if ($this->modalAction === 'assign') {
            $this->membershipStartDate = Carbon::now()->format('Y-m-d');
        }
    }

    public function loadFrequenciesForMembership($membershipId)
    {
        $this->availableFrequencies = $membershipId
            ? MembershipFrequency::where('membership_id', $membershipId)
                ->with('frequency:id,name')
                ->get()
                ->map(fn($mf) => ['id' => $mf->id, 'name' => $mf->frequency->name])
                ->toArray()
            : [];
    }

    public function updatedMembershipStartDate($value)
    {
        if ($this->modalAction === 'edit' && $this->latestMembership) {
            $this->calculateDatesBasedOnFrequency($value);
        }
    }

    public function calculateDate($frequencyName, $startDate)
    {
        $endDate = $startDate->copy();
        switch ($frequencyName) {
            case 'weekly':
                $endDate->addWeek()->subDay();
                break;
            case 'monthly':
                $endDate->addMonth()->subDay();
                break;
            case 'quarterly':
                $endDate->addMonths(3)->subDay();
                break;
            case 'half-yearly':
                $endDate->addMonths(6)->subDay();
                break;
            case 'yearly':
                $endDate->addYear()->subDay();
                break;
            case 'daily':
                $endDate->addDay();
                break;
            default:
                $endDate->addMonth()->subDay(); // Default to monthly
        }
        return $endDate;
    }

    public function calculateDatesBasedOnFrequency($startDate = null)
    {
        $frequency = $this->modalAction === 'edit' 
            ? $this->latestMembership->membershipFrequency 
            : ($this->selectedFrequencyId 
                ? MembershipFrequency::with('frequency')->find($this->selectedFrequencyId) 
                : null);

        if (!$frequency) return;

        // Use provided start date, current start date, or now
        $startDate = Carbon::parse($startDate ?? $this->membershipStartDate ?? now());
        $this->membershipStartDate = $startDate->format('Y-m-d');
        
        $frequencyName = strtolower($frequency->frequency->slug);
        $endDate = $this->calculateDate($frequencyName, $startDate);
        
        $this->membershipEndDate = $endDate->format('Y-m-d');
        
        if ($this->autoRenewal) {
            // Always set next renewal date to end date + 1 day
            $this->nextRenewalDate = $endDate->copy()->addDay()->format('Y-m-d');
        }
    }

    public function updatedSelectedFrequencyId($value)
    {
        if ($this->modalAction === 'renew' || $this->modalAction === 'assign') {
            $this->calculateDatesBasedOnFrequency($this->membershipStartDate);
        }
    }

    public function updatedAutoRenewal($value)
    {
        if (!$this->membershipEndDate) {
            $this->nextRenewalDate = null;
            $this->lastRenewalDate = null;
            return;
        }
    
        $this->nextRenewalDate = $value ? Carbon::parse($this->membershipEndDate)->addDay()->format('Y-m-d') : null;
    
        if ($this->modalAction === 'assign') {
            $this->lastRenewalDate = null;
        }
    }    

    public function openModal($action)
    {
        $this->resetValidation();
        $this->modalAction = $action;
        $this->showRenewalFields = ($action === 'renew');
        
        // Default values for new assignment
        $this->selectedMembershipId = null;
        $this->availableFrequencies = [];
        $this->selectedFrequencyId = null;
        $this->membershipStartDate = Carbon::now()->format('Y-m-d');
        $this->membershipEndDate = null;
        $this->autoRenewal = false;
        $this->lastRenewalDate = null;
        $this->nextRenewalDate = null;
    
        if (!$this->latestMembership) {
            $this->showModal = true;
            return;
        }
    
        // Common values for both edit and renew actions
        if ($action === 'edit' || $action === 'renew') {
            $this->selectedMembershipId = $this->latestMembership->membership_id;
            $this->loadFrequenciesForMembership($this->selectedMembershipId);
            $this->selectedFrequencyId = $this->latestMembership->membership_frequency_id;
            $this->autoRenewal = $this->latestMembership->auto_renewal;
            $this->nextRenewalDate = $this->latestMembership->next_renewal_date;
            $this->lastRenewalDate = $this->latestMembership->membership_start_date?->format('Y-m-d');
        }
    
        if ($action === 'edit') {
            $this->membershipStartDate = $this->latestMembership->membership_start_date->format('Y-m-d');
            $this->membershipEndDate = $this->latestMembership->membership_expiry_date->format('Y-m-d');
    
            if ($this->autoRenewal) {
                $this->nextRenewalDate = Carbon::parse($this->membershipEndDate)->addDay()->format('Y-m-d');
            }
        } elseif ($action === 'renew') {
            $this->membershipStartDate = max(
                $this->latestMembership->membership_expiry_date->addDay()->format('Y-m-d'),
                Carbon::tomorrow()->format('Y-m-d')
            );
            $this->calculateDatesBasedOnFrequency($this->membershipStartDate);
        }
    
        $this->showModal = true;
    }    

    public function confirmRenew()
    {
        $this->alert('warning', __('members.renew_membership'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'handleConfirmRenew',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('members.confirm_renew'),
            'data' => [
                'action' => 'renew',
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }
    public function handleConfirmRenew()
    {
        $this->openModal('renew');
    }


    public function closeModal()
    {
        $this->reset([
            'showModal',
            'showRenewalFields',
            'modalAction',
            'selectedMembershipId',
            'selectedFrequencyId',
            'membershipStartDate',
            'membershipEndDate',
            'autoRenewal',
            'availableFrequencies',
            'lastRenewalDate',
            'nextRenewalDate'
        ]);
    
        $this->resetValidation();
    }    

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $isEdit = $this->modalAction === 'edit';
            $isRenew = $this->modalAction === 'renew';
    
            if ($isEdit) {
                // Update existing membership
                $this->latestMembership->update([
                    'membership_start_date' => $this->membershipStartDate,
                    'membership_expiry_date' => $this->membershipEndDate,
                ]);

                // Update auto-renewal dates if enabled
                if ($this->latestMembership->auto_renewal) {
                    $this->latestMembership->update([
                        'next_renewal_date' => Carbon::parse($this->membershipEndDate)->addDay()->format('Y-m-d'),
                    ]);

                    // Update upcoming renewed membership if exists
                    $upcomingMembership = UserMembership::where([
                        'parent_membership_id' => $this->latestMembership->id,
                        'membership_status' => 'upcoming'
                    ])->first();

                    if ($upcomingMembership) {
                        $upcomingMembership->update([
                            'membership_start_date' => Carbon::parse($this->membershipEndDate)->addDay()->format('Y-m-d'),
                            'membership_expiry_date' => $this->calculateDate(
                                $upcomingMembership->membershipFrequency->frequency->slug,
                                Carbon::parse($this->membershipEndDate)->addDay()
                            ),
                        ]);
                    }
                }

                // Update corresponding invoice dates if exists
                $existingInvoice = Invoice::whereHas('details', function ($query) {
                    $query->where('membership_frequency_id', $this->latestMembership->membership_frequency_id);
                })
                ->where('user_id', $this->user->id)
                ->whereHas('details', function ($query) {
                    $query->where('name', 'like', '%' . $this->latestMembership->membership->name . '%');
                })
                ->latest()
                ->first();

                if ($existingInvoice) {
                    $existingInvoice->update([
                        'invoice_date' => $this->membershipStartDate,
                        'due_date' => Carbon::parse($this->membershipStartDate)->addDays(7),
                    ]);
                }

                $message = 'Membership dates updated successfully';
            } else {
                // Determine membership status
                $membershipStatus = ($isRenew && optional($this->latestMembership)->membership_expiry_date->format('Y-m-d') >= date('Y-m-d')) // compare the two dates  not the time
                    ? 'Upcoming' 
                    : 'Active';
    
                //  === Handle Renewal & Auto-Renewal Logic ===
                $lastRenewalDate = null;
                $nextRenewalDate = null;    

                $this->calculateDatesBasedOnFrequency($this->membershipStartDate);

                $lastRenewalDate = $isRenew ? $this->latestMembership?->membership_start_date : null;
                $nextRenewalDate = $isRenew && $this->autoRenewal ? $this->nextRenewalDate : null;

                $newMembership = UserMembership::create([
                    'user_id' => $this->user->id,
                    'membership_id' => $this->selectedMembershipId,
                    'membership_frequency_id' => $this->selectedFrequencyId,
                    'membership_start_date' => $this->membershipStartDate,
                    'membership_expiry_date' => $this->membershipEndDate,
                    'membership_status' => $membershipStatus,
                    'auto_renewal' => $this->autoRenewal,
                    'last_renewal_date' => $this->lastRenewalDate,
                    'next_renewal_date' => $this->nextRenewalDate,
                    'parent_membership_id' => $isRenew ? optional($this->latestMembership)->id : null
                ]);
    
                if ($membershipStatus === 'Active') {
                    // Update latest_membership_id in users table if the new membership is active
                    $this->user->update(['latest_membership_id' => $newMembership->id]);
                };

                // Generate invoice for both new assignments and renewals
                $this->generateInvoice($newMembership);
    
                $message = $isRenew 
                    ? __('members.success_renew') 
                    : __('members.success_assignmment');
            }
    
            $this->alert('success', $message);
            $this->closeModal();
            $this->loadMembershipData();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('members.operation_failed', [
                'action' => $this->modalAction,
                'message' => $e->getMessage()
            ]));
        }
    }
    
    public function generateInvoice($newMembership)
    {
        try {
            DB::beginTransaction();

            // Get the previous invoice for this membership type
            $previousInvoice = Invoice::whereHas('details', function ($query) use ($newMembership) {
                $query->where('membership_frequency_id', $newMembership->membership_frequency_id);
            })
            ->where('user_id', $this->user->id)
            ->latest()
            ->first();

            // Generate invoice number
            $lastInvoice = Invoice::orderBy('id', 'desc')->first();
            $nextNumber = $lastInvoice ? $lastInvoice->id + 1 : 1;
            $invoiceNumber = $nextNumber <= 100 
                ? str_pad($nextNumber, 3, '0', STR_PAD_LEFT)
                : (string)$nextNumber;

            // Create new invoice
            $invoice = Invoice::create([
                'user_id' => $this->user->id,
                'invoice_date' => now(),
                'due_date' => now()->addDays(7),
                'user_membership_id' => $newMembership->id,
                'invoice_prefix' => 'INV-',
                'invoice_number' => $invoiceNumber,
                'status' => 'unpaid',
                'sub_total' => $newMembership->membershipFrequency->price,
                'discount_type' => '%',
                'discount_value' => 0,
                'discount_amount' => 0,
                'total_amount' => $newMembership->membershipFrequency->price,
                'notes' => __('members.default_renew_note')
            ]);

            // Add membership details to invoice
            $membership = $newMembership->membership;
            $frequency = $newMembership->membershipFrequency->frequency;
            
            $invoiceDetail = InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'user_membership_id' => $newMembership->id,
                'membership_frequency_id' => $newMembership->membership_frequency_id,
                'name' => $membership->name . ' (' . $frequency->name . ')',
                'description' => __('members.description_with_services', [
                    'services' => $membership->services->pluck('name')->implode(', ')
                ]),
                'quantity' => 1,
                'unit_price' => $newMembership->membershipFrequency->price,
                'amount' => $newMembership->membershipFrequency->price
            ]);

            // Copy taxes from previous invoice if exists
            if ($previousInvoice) {
                $previousDetail = $previousInvoice->details->first();
                if ($previousDetail) {
                    foreach ($previousDetail->taxes as $tax) {
                        InvoiceDetailTax::create([
                            'invoice_detail_id' => $invoiceDetail->id,
                            'tax_id' => $tax->tax_id
                        ]);
                    }
                }
            }

            DB::commit();
            return $invoice;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('members.generate_failed', ['message' => $e->getMessage()]));
            return null;
        }
    }

    public function showGenerateInvoice()
    {
        return redirect()->route('invoices.create', ['user_id' => $this->user->id]);
    }

    public function confirmStopAutoRenewal()
    {
        $this->alert('warning', __('members.stop_auto_renew'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'stopAutoRenewal',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('members.confirm_stop_auto_renew'),
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }
    
    public function stopAutoRenewal()
    {
        if (!$this->latestMembership?->auto_renewal) {
            return;
        }
    
        try {
            DB::beginTransaction();

            $this->latestMembership->update([
                'auto_renewal' => false,
                'next_renewal_date' => null,
            ]);
            
            $upcomingMembership = UserMembership::where([
                'parent_membership_id' => $this->latestMembership->id, 
                'membership_status' => 'upcoming'
            ])->first();
            
            if ($upcomingMembership) {
                $upcomingMembership->delete();
            }
            
            $this->loadMembershipData(); 
            $this->showStopAutoRenewalButton = false;
            $this->showRenewButton = true;
            
            DB::commit();
            $this->alert('success', __('members.disable_auto_renew'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('members.disable_auto_renewal_failed', ['message' => $e->getMessage()]));
        }
    }
    
    public function removeUpcomingRenewedMembership()
    {
        $this->alert('warning', __('members.remove_upcoming'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'handleRemoveUpcomingRenewedMembership',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('members.remove'),
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function handleRemoveUpcomingRenewedMembership()
    {
        try {
            DB::beginTransaction();
            $this->upcomingRenewedMembership->delete(); 
            $this->loadMembershipData(); 
            $this->alert('success', __('members.remove_success'));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('members.remove_upcoming_membership_failed', ['message' => $e->getMessage()]));
        }
    }

    public function render()
    {
        return view('livewire.member.membership-details');
    }
} 