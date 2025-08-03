<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-md mb-10 dark:bg-green-900 dark:border-green-700 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif
    <!-- Filter Section -->
    <div class="pb-3 border-b border-gray-200 dark:border-gray-700 mt-2">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0">
            <!-- Main Filter Container -->
            <div class="w-full">
                <!-- All Filters in One Row -->
                <div class="flex flex-wrap items-end gap-4">
                    <!-- Paid On Date Filter -->
                    <div class="mt-2">
                        <div class="relative">
                            <div class="absolute -top-5 left-0 text-xs text-gray-500 dark:text-gray-400">{{ __('finance.payments.paid_on') }}</div>
                            <input 
                                type="date"
                                wire:model.live="paidOnDate" 
                                class="w-48 py-1.5 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer transition-all duration-200 hover:border-indigo-300 dark:text-white dark:placeholder:text-gray-400 dark:bg-gray-800 dark:border-gray-700"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        @error('paidOnDate') 
                            <span class="text-xs text-red-500 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Search -->
                    <div class="relative w-48">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input 
                            type="search" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="{{ __('finance.payments.search_term') }}" 
                            class="w-full py-1.5 text-gray-700 placeholder:text-gray-500 text-sm pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:text-white dark:placeholder:text-gray-400 dark:bg-gray-800 dark:border-gray-700"
                        >
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="inline-flex justify-between w-48 items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:text-white dark:bg-gray-800 dark:border-gray-700">
                                    <span>
                                        {{ $status ? __('finance.payments.status.' . $status) : __('common.all_status') }}
                                    </span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1 dark:border dark:border-gray-700">
                                    <x-dropdown-link 
                                        :selected="!$status"
                                        wire:click="$set('status', '')"
                                        @click="open = false"
                                        class="dark:text-gray-300 dark:hover:bg-gray-700"
                                    >
                                        {{ __('common.all_status') }}
                                    </x-dropdown-link>
                                    @foreach(__('finance.payments.status') as $key => $label)
                                        <x-dropdown-link 
                                            :selected="$status === $key"
                                            wire:click="$set('status', '{{ $key }}')"
                                            @click="open = false"
                                            class="dark:text-gray-300 dark:hover:bg-gray-700"
                                        >
                                            {{ $label }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Payment Mode Filter -->
                    <div>
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="inline-flex justify-between w-48 items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:text-white dark:bg-gray-800 dark:border-gray-700">
                                    <span>
                                        {{ $paymentModeOptions[$paymentMode] ?? __('finance.payments.all_payment_modes') }}
                                    </span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1 dark:border dark:border-gray-700">
                                    <x-dropdown-link 
                                        :selected="!$paymentMode"
                                        wire:click="$set('paymentMode', '')"
                                        @click="open = false"
                                        class="dark:text-gray-300 dark:hover:bg-gray-700"
                                    >
                                        {{ __('finance.payments.all_payment_modes') }}
                                    </x-dropdown-link>
                                    @foreach($paymentModeOptions as $key => $label)
                                        <x-dropdown-link 
                                            :selected="$paymentMode === $key"
                                            wire:click="$set('paymentMode', '{{ $key }}')"
                                            @click="open = false"
                                            class="dark:text-gray-300 dark:hover:bg-gray-700"
                                        >
                                            {{ $label }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Reset Filters Button -->
                    @if($search || $status || $paymentMode || $paidOnDate)
                        <button 
                            wire:click="resetFilters"
                            class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 transition-colors duration-200 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800"
                        >
                            {{ __('common.clear_filters') }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="overflow-x-auto bg-white rounded-lg shadow dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('common.id')}}</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('finance.invoices.invoice')}} #</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('finance.payments.client')}}</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('finance.payments.amount')}}</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('finance.payments.paid_on')}}</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('finance.payments.mode')}}</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('common.status')}}</th>
                        @if(auth()->user()->getCachedPermissions()->contains('edit_payment') || auth()->user()->getCachedPermissions()->contains('delete_payment') || auth()->user()->getCachedPermissions()->contains('view_payments') || auth()->user()->getCachedPermissions()->contains('download_payment_receipt'))
                        <th scope="col" class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('common.actions')}}</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-sm dark:text-gray-300">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('payments.show', $payment->id) }}" class="hover:text-gray-900 hover:underline dark:hover:text-white">
                                    {{ $payment->id }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->invoice)
                                    <a href="{{ route('invoices.show', $payment->invoice->id) }}" class="hover:text-gray-900 dark:hover:text-white">
                                        {{ $payment->invoice->invoice_number ?? '--' }}
                                    </a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">--</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->user)
                                    <div class="relative group">
                                        <div class="truncate max-w-[200px]" x-data="{ isTruncated: false }" x-init="isTruncated = $el.scrollWidth > $el.clientWidth">
                                            {{ $payment->user->name }}
                                        </div>
                                        <span x-show="isTruncated" class="absolute bottom-full left-0 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 mb-1">
                                            {{ $payment->user->name }}
                                            <div class="absolute -bottom-1 left-4 w-2 h-2 bg-gray-900 dark:bg-white transform rotate-45"></div>
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">--</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ currency_format($payment->amount_paid) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $payment->payment_date->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap capitalize">
                                {{ Str::replace('_', ' ', $payment->payment_mode) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusStyles = [
                                        'completed' => 'bg-green-100 text-green-900 dark:bg-green-900 dark:text-green-200',
                                        'pending' => 'bg-yellow-100 text-yellow-900 dark:bg-yellow-900 dark:text-yellow-200',
                                        'failed' => 'bg-red-100 text-red-900 dark:bg-red-900 dark:text-red-200',
                                        'partially_paid' => 'bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-200',
                                        'cancelled' => 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-200',
                                    ];

                                    $statusClass = $statusStyles[$payment->status] ?? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-200';
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full capitalize {{ $statusClass }}">
                                    {{ Str::replace('_', ' ', $payment->status) }}
                                </span>
                            </td>

                            @if(auth()->user()->getCachedPermissions()->contains('edit_payment') || auth()->user()->getCachedPermissions()->contains('delete_payment') || auth()->user()->getCachedPermissions()->contains('view_payments') || auth()->user()->getCachedPermissions()->contains('download_payment_receipt'))
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-3">
                                    @hasCachedPermission('view_payments')
                                    <button wire:click="viewPayment({{ $payment->id }})" 
                                        class="text-gray-600 hover:text-blue-900 dark:text-gray-300 dark:hover:text-blue-600 relative group"
                                        aria-label="{{ __('common.view') }}">
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('common.view') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    @endhasCachedPermission
                                    @hasCachedPermission('edit_payment')
                                    <button wire:click="editPayment({{ $payment->id }})" 
                                        class="text-gray-600 hover:text-yellow-900 dark:text-gray-300 dark:hover:text-yellow-600 relative group"
                                        aria-label="{{ __('common.edit') }}">
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('common.edit') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @endhasCachedPermission
                                    @hasCachedPermission('download_payment_receipt')
                                    <button wire:click="downloadPayment({{ $payment->id }})" 
                                        class="text-gray-600 hover:text-green-900 dark:text-gray-300 dark:hover:text-green-600 relative group"
                                        aria-label="{{ __('common.download') }}">
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('common.download') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </button>
                                    @endhasCachedPermission
                                    @hasCachedPermission('delete_payment')
                                    <button wire:click="handleDeletePayment({{ $payment->id }})" 
                                        class="text-gray-600 hover:text-red-900 dark:text-gray-300 dark:hover:text-red-600 relative group"
                                        aria-label="{{ __('common.delete') }}">
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('common.delete') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    @endhasCachedPermission
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300">
                                {{ __('finance.payments.not_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</div>
