<div>
    <div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800 dark:text-gray-200">
        <!-- Filter Section -->
        <div class="pb-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0">
                <!-- Main Filter Container -->
                <div class="w-full">
                    <!-- All Filters in One Row -->
                    <div class="flex flex-wrap items-end gap-4">
                        <!-- Date Range Picker -->
                        <div class="mt-2">
                            <div class="flex flex-wrap   items-center gap-2">
                                <div class="relative">
                                    <div class="absolute -top-5 left-0 text-xs text-gray-500 dark:text-gray-400">{{ __('finance.invoices.date_range') }}</div>
                                    <input 
                                        type="date"
                                        wire:model.live="dateRangeStart" 
                                        class="w-48 py-1.5 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer transition-all duration-200 hover:border-indigo-300 dark:text-white dark:placeholder:text-gray-400 dark:bg-gray-800 dark:border-gray-700"
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <span class="text-gray-500 dark:text-gray-400 font-xs">{{ __('finance.invoices.to') }}</span>
                                <div class="relative">
                                    <input 
                                        type="date" 
                                        wire:model.live="dateRangeEnd" 
                                        min="{{ $dateRangeStart }}"
                                        @if(!$dateRangeStart) disabled @endif
                                        class="w-48 py-1.5 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer transition-all duration-200 hover:border-indigo-300 dark:text-white dark:placeholder:text-gray-400 dark:border-gray-700 @if(!$dateRangeStart) bg-gray-100 dark:bg-gray-500 @else dark:bg-gray-800 @endif"
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            @error('dateRangeStart') 
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                            @error('dateRangeEnd') 
                                <span class="text-xs text-red-500">{{ $message }}</span>
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
                                placeholder="{{ __('finance.invoices.search') }}" 
                                class="w-full py-1.5 text-gray-700 placeholder:text-gray-500 text-sm pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:text-white dark:placeholder:text-gray-400 dark:bg-gray-800 dark:border-gray-700"
                            >
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button type="button" class="inline-flex justify-between w-48 items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:text-white dark:bg-gray-800 dark:border-gray-700">
                                        <span class="capitalize">
                                            {{ $status ? __('finance.invoices.status.' . $status) : __('common.all_status') }}
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
                                        @foreach(__('finance.invoices.status') as $key => $label)
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

                        <!-- Reset Filters Button -->
                        @if($search || $status || $dateRangeStart || $dateRangeEnd)
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

        <!-- Table Section with top margin -->
        <div class="mt-6">
            <div class="overflow-x-auto bg-white rounded-lg shadow dark:bg-gray-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('finance.invoices.invoice') }}</th>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('finance.invoices.client') }}</th>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('finance.invoices.total_amount') }}</th>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('finance.invoices.invoice_date') }}</th>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('common.status')}}</th>
                            @if(auth()->user()->getCachedPermissions()->contains('edit_invoice') || auth()->user()->getCachedPermissions()->contains('delete_invoice') || auth()->user()->getCachedPermissions()->contains('view_invoices'))
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('common.actions') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-sm dark:text-gray-300">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="text-gray-600 hover:font-bold hover:underline dark:text-gray-300 dark:hover:text-white">
                                        {{ $invoice->invoice_prefix }}{{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($invoice->user->avatar)
                                            <img src="{{ $invoice->user->avatar }}" alt="{{ $invoice->user->name }}" class="w-8 h-8 rounded-full mr-2">
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <div class="relative group">
                                                <div class="truncate max-w-[200px]" x-data="{ isTruncated: false }" x-init="isTruncated = $el.scrollWidth > $el.clientWidth">
                                                    <div class="font-medium">{{ $invoice->user->name }}</div>
                                                </div>
                                                <span x-show="isTruncated" class="absolute bottom-full left-0 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 mb-1">
                                                    <div>{{ $invoice->user->name }}</div>
                                                    <div class="absolute -bottom-1 left-4 w-2 h-2 bg-gray-900 dark:bg-white transform rotate-45"></div>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <div>Total: {{ currency_format($invoice->total_amount) }}</div>
                                        <div class="text-green-600 dark:text-green-400">{{ __('finance.invoices.paid') }}: {{ currency_format($invoice->paid_amount) }}</div>
                                        <div class="text-red-600 dark:text-red-400">{{ __('finance.invoices.unpaid') }}: {{ currency_format($invoice->total_amount - $invoice->paid_amount) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $invoice->invoice_date->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full capitalize {{ 
                                        match($invoice->status) {
                                            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'partially_paid' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'unpaid' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                        }
                                    }}">
                                        {{ __('finance.invoices.status.' . $invoice->status) }}
                                    </span>
                                </td>
                                @if(auth()->user()->getCachedPermissions()->contains('edit_invoice') || auth()->user()->getCachedPermissions()->contains('delete_invoice') || auth()->user()->getCachedPermissions()->contains('view_invoices') || auth()->user()->getCachedPermissions()->contains('make_payment'))
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex space-x-3">
                                        @hasCachedPermission('make_payment')
                                        @if($invoice->status === 'unpaid' || $invoice->status === 'partially_paid')
                                            <button wire:click="makePayment({{ $invoice->id }})"
                                               class="text-gray-600 hover:text-green-600 dark:text-gray-300 dark:hover:text-green-400 relative group"
                                               aria-label="{{ __('finance.payments.make_payment') }}">
                                                <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                    {{ __('finance.payments.make_payment') }}
                                                </span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </button>
                                        @endif
                                        @endhasCachedPermission
                                        @hasCachedPermission('view_invoices')
                                        <a href="{{ route('invoices.show', $invoice->id) }}" 
                                           class="text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 relative group"
                                           aria-label="{{ __('finance.invoices.view') }}">
                                            <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                {{ __('finance.invoices.view') }}
                                            </span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        @endhasCachedPermission
                                        @hasCachedPermission('edit_invoice')
                                        @if($invoice->status === 'unpaid')
                                            <button wire:click="editInvoice({{ $invoice->id }})"
                                               class="text-gray-600 hover:text-yellow-600 dark:text-gray-300 dark:hover:text-yellow-400 relative group"
                                               aria-label="{{ __('finance.invoices.edit') }}">
                                                <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                    {{ __('finance.invoices.edit') }}
                                                </span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                        @endif
                                        @endhasCachedPermission
                                        @hasCachedPermission('delete_invoice')
                                        @if($invoice->status === 'unpaid' && $invoice->id === $latestInvoiceId)
                                            <button wire:click="handleDeleteInvoice({{ $invoice->id }})"
                                                class="text-gray-600 hover:text-red-600 dark:text-gray-300 dark:hover:text-red-400 relative group"
                                                aria-label="{{ __('finance.invoices.delete') }}">
                                                <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                    {{ __('finance.invoices.delete') }}
                                                </span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                        @endhasCachedPermission
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300">
                                    {{ __('finance.invoices.no_invoice') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Modal -->
    @if($showPaymentModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-500 dark:bg-opacity-75" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-800">
                <!-- Modal Header -->
                <div class="bg-white px-6 py-4 border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700 rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200" id="modal-title">
                                    {{ __('finance.payments.record') }} <span class="text-sm text-gray-500 dark:text-gray-400 font-normal"> ({{ __('finance.invoices.invoice') }} #{{ $selectedInvoice->invoice_number }})</span>
                                </h3>
                            </div>
                        </div>
                        {{-- <button wire:click="closePaymentModal" type="button" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400">
                            <span class="sr-only">{{ __('common.close') }}</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button> --}}
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="bg-white px-6 py-5 dark:bg-gray-800">
                    <div class="space-y-6">
                        <!-- Invoice Summary -->
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3 dark:bg-gray-700">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ __('finance.invoices.total_amount') }}</span>
                                <span class="text-lg font-semibold text-gray-900 dark:text-gray-200">{{ currency_format($selectedInvoice->total_amount) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ __('finance.invoices.due') }}</span>
                                <span class="text-lg font-semibold text-red-600 dark:text-red-400">{{ currency_format($selectedInvoice->total_amount - $selectedInvoice->paid_amount) }}</span>
                            </div>
                        </div>

                        <!-- Payment Amount -->
                        <div>
                            <label for="paymentAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.payments.payment_amt') }}</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">{{ currency()->symbol }}</span>
                                </div>
                                <input type="number" wire:model.live="paymentAmount" id="paymentAmount" step="0.01" min="0.01" max="{{ $selectedInvoice->total_amount - $selectedInvoice->paid_amount }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 py-2.5 text-base border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    placeholder="0.00">
                            </div>
                            @error('paymentAmount') 
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Mode -->
                        <div>
                            <label for="paymentMode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('finance.payments.mode')}}</label>
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button type="button" class="capitalize w-full relative text-left text-sm rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                        {{ $paymentModeOptions[$paymentMode] ?? __('finance.payments.mode_select') }}
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>
                                </x-slot>
                            
                                <x-slot name="content">
                                    <div class="py-1 dark:border dark:border-gray-700">
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
                            @error('paymentMode') 
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse sm:justify-start sm:space-x-reverse rounded-lg sm:space-x-3 dark:bg-gray-800">
                    <button wire:click="savePayment" type="button"
                        class="w-full inline-flex justify-center items-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm transition-colors duration-200 dark:bg-blue-500 dark:hover:bg-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('finance.payments.save_payment') }}
                    </button>
                    <button wire:click="closePaymentModal" type="button"
                        class="mt-3 w-full inline-flex justify-center items-center rounded-lg border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ __('common.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
