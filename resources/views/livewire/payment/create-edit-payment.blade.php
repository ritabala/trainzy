<div>
    <div class="bg-white shadow-xl rounded-lg overflow-hidden dark:bg-gray-800">
        <!-- Payment Information -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('finance.payments.payment_information') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $isEditMode ? __('finance.payments.update_payment_details') : __('finance.payments.record_payment') }}</p>
            </div>

            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Member Selection -->
                    <div>
                        <label for="selectedUserId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.payments.member_optional') }}</label>
                        <div class="relative" x-data="{ open: false }">
                            <div class="relative">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left text-sm rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-3 py-2 bg-white dark:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-300">
                                    @if($selectedUserId)
                                        @php $selectedUser = collect($users)->firstWhere('id', $selectedUserId); @endphp
                                        @if($selectedUser)
                                            <span class="text-gray-900 dark:text-gray-200 truncate block">{{ $selectedUser['name'] }}</span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('finance.payments.select_member') }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">{{ __('finance.payments.select_member') }}</span>
                                    @endif
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                            </div>

                            <div x-show="open" @click.away="open = false"
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 shadow-lg rounded-md border border-gray-300 dark:border-gray-600 py-1">
                                <!-- Search input -->
                                <div class="px-3 py-2">
                                    <input type="text" 
                                        wire:model.live.debounce.300ms="userSearchQuery"
                                        placeholder="{{ __('finance.payments.search_members') }}"
                                        class="w-full text-sm border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
                                </div>

                                <!-- Loading indicator -->
                                <div wire:loading wire:target="userSearchQuery" class="px-3 py-2">
                                    <div class="flex items-center justify-center">
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                                    </div>
                                </div>

                                <!-- Members list -->
                                <div class="max-h-60 overflow-y-auto">
                                    <!-- Add reset option at the top -->
                                    <button type="button" 
                                        wire:click="$set('selectedUserId', null)"
                                        @click="open = false"
                                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 border-b border-gray-200 dark:border-gray-600 dark:text-gray-300">
                                        <div class="font-medium text-gray-900 dark:text-gray-200">{{ __('finance.payments.select_member') }}</div>
                                        <div class="text-gray-500 text-xs dark:text-gray-400">{{ __('finance.payments.clear_current_selection') }}</div>
                                    </button>

                                    @if(empty($users))
                                        <div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('finance.payments.no_members_found') }}
                                        </div>
                                    @else
                                        @foreach($users as $user)
                                            <button type="button" 
                                                wire:click="$set('selectedUserId', {{ $user['id'] }})"
                                                @click="open = false"
                                                class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"
                                                :class="{ 'bg-gray-50 dark:bg-gray-600': $wire.selectedUserId == {{ $user['id'] }} }">
                                                <div class="font-medium text-gray-900 dark:text-gray-200 truncate">{{ $user['name'] }}</div>
                                                <div class="text-gray-500 dark:text-gray-400 text-xs truncate">{{ $user['email'] }}</div>
                                            </button>
                                        @endforeach

                                        <!-- Loading more indicator -->
                                        <div wire:loading wire:target="loadMoreUsers" class="px-3 py-2">
                                            <div class="flex items-center justify-center">
                                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                                            </div>
                                        </div>

                                        <!-- Load more trigger -->
                                        <div x-intersect="$wire.loadMoreUsers()" class="h-4"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @error('selectedUserId') 
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Invoice Selection -->
                    <div>
                        <label for="selectedInvoiceId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.payments.invoice_number_optional') }}</label>
                        <div class="relative" x-data="{ open: false }">
                            <div class="relative">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left text-sm rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-3 py-2 bg-white dark:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-300">
                                    @if($selectedInvoiceId)
                                        @php $selectedInvoice = collect($invoices)->firstWhere('id', $selectedInvoiceId); @endphp
                                        @if($selectedInvoice)
                                            {{ __('finance.payments.inv_no') }}{{ $selectedInvoice['invoice_number'] }} - {{ __('finance.payments.total') }} {{ currency_format($selectedInvoice['total_amount']) }} {{ __('finance.payments.due') }} {{ currency_format($selectedInvoice['due_amount']) }}
                                        @else
                                            {{ __('finance.payments.select_invoice') }}
                                        @endif
                                    @else
                                        {{ __('finance.payments.select_invoice') }}
                                    @endif
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                            </div>

                            <div x-show="open" @click.away="open = false"
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 shadow-lg rounded-md border border-gray-300 dark:border-gray-600 py-1">
                                <div class="py-1">
                                    <!-- Add reset option at the top -->
                                    <button type="button" 
                                        wire:click="$set('selectedInvoiceId', null)"
                                        @click="open = false"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-200 border-b border-gray-200 dark:border-gray-600">
                                        <div class="font-medium">{{ __('finance.payments.select_invoice') }}</div>
                                        <div class="text-gray-500 dark:text-gray-400 text-xs">{{ __('finance.payments.clear_current_selection') }}</div>
                                    </button>

                                    @foreach($invoices as $invoice)
                                        <button type="button" 
                                            wire:click="$set('selectedInvoiceId', '{{ $invoice['id'] }}')" 
                                            @click="open = false"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-200 {{ $selectedInvoiceId == $invoice['id'] ? 'bg-gray-50 dark:bg-gray-600 font-medium' : '' }}">
                                            <div>
                                                <span class="font-medium mr-1">{{ __('finance.payments.inv_no') }}{{ $invoice['invoice_number'] }}</span>
                                                <span class="text-gray-500 dark:text-gray-400 mr-2"> - </span>
                                                <span class="text-gray-500 dark:text-gray-400 mr-2">{{ __('finance.payments.total') }} {{ currency_format($invoice['total_amount']) }}</span>
                                                <span class="text-red-600 dark:text-red-400">{{ __('finance.payments.due') }} {{ currency_format($invoice['due_amount']) }}</span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('selectedInvoiceId') 
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount_paid" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.payments.amount') }}</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">{{ currency()->symbol }}</span>
                            </div>
                            <input type="number" wire:model.blur="amount_paid" id="amount_paid" step="0.01" 
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 py-2.5 text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300"
                                placeholder="0.00">
                        </div>
                        @error('amount_paid') 
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.payments.paid_on') }}</label>
                        <input type="datetime-local" wire:model.live="payment_date" id="payment_date"
                            class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
                        @error('payment_date') 
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Mode -->
                    <div>
                        <label for="payment_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.payments.mode') }}</label>
                        <x-dropdown align="left">
                            <x-slot name="trigger">
                                <button type="button" class="inline-flex capitalize justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ $paymentModeOptions[$payment_mode] ?? __('finance.payments.mode_select') }}
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="border border-gray-300 dark:border-gray-600">
                                @foreach($paymentModeOptions as $key => $label)
                                    <x-dropdown-link 
                                        :selected="$payment_mode === $key"
                                        wire:click="$set('payment_mode', '{{ $key }}')"
                                        @click="open = false"
                                        class="dark:text-gray-300 dark:hover:bg-gray-600"
                                    >
                                        {{ $label }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                        @error('payment_mode') 
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Transaction Number -->
                    <div>
                        <label for="transaction_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.payments.transaction_no') }}</label>
                        <input type="text" wire:model.live="transaction_no" id="transaction_no"
                            class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300"
                            placeholder="{{ __('finance.payments.enter_transaction_number') }}">
                        @error('transaction_no')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Remarks Section -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('finance.payments.additional_information') }}</h3>
                    <div>
                        <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.payments.remarks') }}</label>
                        <textarea wire:model.live="remarks" id="remarks" rows="3"
                            class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300"
                            placeholder="{{ __('finance.payments.add_additional_notes_or_remarks') }}"></textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-sm text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ $isEditMode ? __('finance.payments.update_payment') : __('finance.payments.create_payment') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
