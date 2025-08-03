<div class="container mx-auto">
    <!-- Action Buttons with Enhanced Design -->
    <div class="mb-4 flex flex-col sm:flex-row justify-start gap-3">
        <!-- Primary Action -->
        @if($invoice->status !== 'paid')
        <button wire:click="proceedToPayment" class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 hover:shadow-lg transition-all duration-200 flex items-center justify-center group text-sm dark:bg-blue-500 dark:hover:bg-blue-600">
            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            {{ __('finance.invoices.proceed_to_payment') }}
        </button>
        @endif

        <!-- Payment Modal -->
        @if($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-500 dark:bg-opacity-75" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <!-- Modal Header -->
                    <div class="bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-100 dark:border-gray-700  rounded-t-lg">
                        <div class="flex items-center justify-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3 flex flex-col">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" id="modal-title">
                                    {{ __('finance.payments.record') }} <span class="text-sm text-gray-500 dark:text-gray-400 font-normal"> ({{ __('finance.invoices.invoice') }} #{{ $invoice->invoice_number }})</span>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="bg-white dark:bg-gray-800 px-6 py-5">
                        <div class="space-y-6">
                            <!-- Amount Summary -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ __('finance.invoices.total_amount') }}</span>
                                    <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ currency_format($invoice->total_amount) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ __('finance.invoices.due') }}</span>
                                    <span class="text-lg font-semibold text-red-600 dark:text-red-400">{{ currency_format($this->dueAmount) }}</span>
                                </div>
                            </div>

                            <!-- Payment Form -->
                            <div class="space-y-4">
                                <!-- Payment Amount -->
                                <div>
                                    <label for="paymentAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.payments.payment_amt') }}</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">{{ currency()->symbol }}</span>
                                        </div>
                                        <input type="number" wire:model.live="paymentAmount" id="paymentAmount" step="0.01" min="0.01" max="{{ $invoice->total_amount }}"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 py-2.5 text-base border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-300"
                                            placeholder="0.00">
                                    </div>
                                    @error('paymentAmount') 
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Payment Mode -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.payments.mode') }}</label>
                                    <x-dropdown align="left" width="48">
                                        <x-slot name="trigger">
                                            <button type="button" class="capitalize mt-1 inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                {{ $paymentModeOptions[$paymentMode] ?? __('finance.payments.mode_select') }}
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <div class="py-1 dark:border dark:border-gray-700">
                                            @foreach($paymentModeOptions as $key => $label)
                                                <x-dropdown-link 
                                                    :selected="$paymentMode === $key"
                                                    wire:click="$set('paymentMode', '{{ $key }}')"
                                                    @click="open = false"
                                                    class="dark:text-gray-300 dark:hover:bg-gray-600"
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
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 flex flex-col sm:flex-row-reverse sm:justify-start sm:space-x-reverse sm:space-x-3  rounded-lg">
                        <button wire:click="savePayment" type="button"
                            class="w-full inline-flex justify-center items-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-blue-600 dark:bg-blue-500 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('finance.payments.save_payment') }}
                        </button>
                        <button wire:click="closePaymentModal" type="button"
                            class="mt-3 w-full inline-flex justify-center items-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2.5 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
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

        <!-- Secondary Actions -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button wire:click.prevent="sendInvoice" class="w-full sm:w-auto px-6 py-2 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-blue-200 dark:hover:border-blue-500 hover:shadow-md transition-all duration-200 flex items-center justify-center group text-sm">
                <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 group-hover:scale-110 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{ __('finance.invoices.send_invoice') }}
            </button>

            <button wire:click="downloadPdf" class="w-full sm:w-auto px-6 py-2 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-blue-200 dark:hover:border-blue-500 hover:shadow-md transition-all duration-200 flex items-center justify-center group text-sm">
                <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 group-hover:scale-110 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('finance.invoices.download_pdf') }}
            </button>

            <button onclick="window.print()" class="w-full sm:w-auto px-6 py-2 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-blue-200 dark:hover:border-blue-500 hover:shadow-md transition-all duration-200 flex items-center justify-center group text-sm">
                <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 group-hover:scale-110 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                {{ __('common.print') }}
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">

        <!-- Invoice Header with Logo and Status -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ __('finance.invoices.invoice') }}</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">#{{ $invoice->invoice_number }}</p>
            </div>
            <div class="flex flex-col items-end">
                @php
                    $statusStyles = [
                        'paid' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800',
                        'partially_paid' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800',
                        'overdue' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 border border-red-200 dark:border-red-800',
                        'unpaid' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 border border-red-200 dark:border-red-800',
                    ];

                    $defaultStyle = 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600';
                    $statusClass = $statusStyles[$invoice->status] ?? $defaultStyle;
                @endphp

                <span class="px-4 py-2 text-sm rounded-lg font-semibold tracking-wider capitalize {{ $statusClass }}">
                    {{ Str::replace('_', ' ', $invoice->status) }}
                </span>

            </div>
        </div>

        <!-- From/To Section with Enhanced Design -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                <div class="mb-6">
                    <h2 class="text-xs tracking-wider text-gray-500 dark:text-gray-400 mb-2">{{ __('finance.invoices.billed_from') }}</h2>
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ gym()->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ gym()->address ?? 'N/A' }}</p>
                </div>
                <div>
                    <h2 class="text-xs tracking-wider text-gray-500 dark:text-gray-400 mb-2">{{ __('finance.invoices.issued_on') }}</h2>
                    <p class="text-gray-800 dark:text-gray-300 text-sm">{{ $invoice->invoice_date->format('d M, Y') }}</p>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg text-right">
                <div class="mb-6">
                    <h2 class="text-xs tracking-wider text-gray-500 dark:text-gray-400 mb-2">{{ __('finance.invoices.billed_to') }}</h2>
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ $invoice->user->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $invoice->user->address }}</p>
                </div>
                <div>
                    <h2 class="text-xs tracking-wider text-gray-500 dark:text-gray-400 mb-2">{{ __('finance.invoices.due_on') }}</h2>
                    <p class="text-gray-800 dark:text-gray-300 text-sm">{{ $invoice->due_date->format('d M, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Invoice Items Table with Enhanced Design -->
        <div class="mb-8 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-600">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="text-left py-4 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                        <th class="text-left py-4 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('finance.invoices.item_name') }}</th>
                        <th class="text-center py-4 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('finance.invoices.quantity') }}</th>
                        <th class="text-right py-4 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('finance.invoices.unit_cost') }}</th>
                        <th class="text-right py-4 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('finance.invoices.total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($invoice->details as $index => $detail)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-600 dark:text-gray-400">{{ $index + 1 }}</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $detail->name }}</td>
                        <td class="py-4 px-4 text-sm text-center text-gray-600 dark:text-gray-400">{{ $detail->quantity }}</td>
                        <td class="py-4 px-4 text-sm text-right text-gray-600 dark:text-gray-400">{{ currency_format($detail->unit_price) }}</td>
                        <td class="py-4 px-4 text-sm text-right font-medium text-gray-800 dark:text-gray-200">{{ currency_format($detail->amount) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Invoice Summary with Professional Design -->
        <div class="pt-4">
            <div class="flex w-full lg:justify-end">
                <div class="w-full lg:w-1/2 bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-600 shadow-sm">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b border-gray-100 dark:border-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        {{ __('finance.invoices.invoice_summary') }}
                    </h3>
                    <div class="space-y-3">
                        <!-- Subtotal Section -->
                        <div class="flex justify-between items-center py-2">
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('finance.invoices.sub_tot') }}</span>
                                <div class="ml-1.5 group relative">
                                    <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 w-48 p-2 bg-gray-800 dark:bg-gray-900 text-white text-xs rounded mb-1 z-10">
                                        {{ __('finance.invoices.total_amount_before_taxes_and_discounts') }}
                                        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 w-2 h-2 bg-gray-800 dark:bg-gray-900 rotate-45"></div>
                                    </div>
                                </div>
                            </div>
                            <span class="text-sm text-gray-800 dark:text-gray-300">{{ currency_format($invoice->sub_total) }}</span>
                        </div>

                        <!-- Tax Section -->
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-3">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('finance.invoices.tax_summary') }}</span>
                                <span class="text-sm text-gray-800 dark:text-gray-300">{{ currency_format(collect($taxSummary)->sum('amount')) }}</span>
                            </div>
                            <div class="pl-4 space-y-1.5">
                                @foreach($taxSummary as $tax)
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $tax['name'] }} ({{ $tax['rate'] }}%)</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ currency_format($tax['amount']) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Discount Section -->
                        @if($invoice->discount_amount > 0)
                        <div class="flex justify-between items-center py-2 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('finance.invoices.disc') }} {{ $invoice->discount_type === '%' ? "({$invoice->discount_value}%)" : '' }}
                            </span>
                            <span class="text-sm text-red-600 dark:text-red-400">-{{ currency_format($invoice->discount_amount) }}</span>
                        </div>
                        @endif

                        <!-- Total Section -->
                        <div class="border-t border-gray-200 dark:border-gray-600 mt-4 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ __('finance.invoices.total_amount') }}</span>
                                <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ currency_format($invoice->total_amount) }}</span>
                            </div>
                            
                            @if($invoice->status !== 'paid')
                            <div class="mt-3 flex flex-col items-end text-xs text-gray-500 dark:text-gray-400">
                                <div class="flex items-center mb-1">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('finance.invoices.due_by') }} {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('finance.invoices.due') }}: &nbsp;<span class="font-semibold text-gray-900 dark:text-gray-100">{{ currency_format($invoice->total_amount - $invoice->paid_amount) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Payment Status -->
                        @if($invoice->status === 'paid')
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-end text-sm text-green-600 dark:text-green-400">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('finance.invoices.paid_in_full') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($invoice->notes)
        <div class="mt-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <h3 class="text-sm tracking-wider text-gray-500 dark:text-gray-400 mb-2">{{ __('finance.invoices.notes') }}</h3>
            <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $invoice->notes }}</p>
        </div>
        @endif

        <!-- Payments Section -->
        @if($invoice->payments->count() > 0)
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('finance.invoices.linked_payments') }}</h2>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                    <!-- Desktop Table (hidden on mobile) -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('finance.payments.payment_id') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('finance.payments.amount') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.date') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('finance.payments.mode') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.status') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($invoice->payments as $payment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            <a href="{{ route('payments.show', $payment->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:underline">
                                                {{ $payment->id }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ currency_format($payment->amount_paid) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $payment->payment_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap capitalize text-sm text-gray-900 dark:text-gray-200">
                                            {{ Str::replace('_', ' ', $payment->payment_mode) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusStyles = [
                                                    'completed' => 'bg-green-100 dark:bg-green-900 text-green-900 dark:text-green-200',
                                                    'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-900 dark:text-yellow-200',
                                                    'failed' => 'bg-red-100 dark:bg-red-900 text-red-900 dark:text-red-200',
                                                    'partially_paid' => 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-200',
                                                ];

                                                $statusClass = $statusStyles[$payment->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-200';
                                            @endphp
                                            <span class="px-2 inline-flex text-sm leading-5 capitalize rounded-full {{ $statusClass }}">
                                                {{ Str::replace('_', ' ', $payment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button wire:click="viewPayment({{ $payment->id }})" 
                                                    class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 relative group"
                                                    aria-label="{{ __('common.view_details') }}">
                                                <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                    {{ __('common.view_details') }}
                                                </span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards (visible only on mobile) -->
                    <div class="md:hidden">
                        @foreach($invoice->payments as $payment)
                            <div class="p-4 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <a href="{{ route('payments.show', $payment->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:underline text-sm font-medium">
                                            {{ __('finance.payments.payment_id') }}: {{ $payment->id }}
                                        </a>
                                        <p class="text-sm text-gray-900 dark:text-gray-200 mt-1">
                                            {{ currency_format($payment->amount_paid) }}
                                        </p>
                                    </div>
                                    @php
                                        $statusStyles = [
                                            'completed' => 'bg-green-100 dark:bg-green-900 text-green-900 dark:text-green-200',
                                            'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-900 dark:text-yellow-200',
                                            'failed' => 'bg-red-100 dark:bg-red-900 text-red-900 dark:text-red-200',
                                            'partially_paid' => 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-200',
                                        ];

                                        $statusClass = $statusStyles[$payment->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-200';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 capitalize rounded-full {{ $statusClass }}">
                                        {{ Str::replace('_', ' ', $payment->status) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    <div>
                                        <p>{{ __('common.date') }}: {{ $payment->payment_date->format('M d, Y') }}</p>
                                        <p class="mt-1 capitalize">{{ __('finance.payments.mode') }}: {{ Str::replace('_', ' ', $payment->payment_mode) }}</p>
                                    </div>
                                    <button wire:click="viewPayment({{ $payment->id }})" 
                                            class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 relative group"
                                            aria-label="{{ __('common.view_details') }}">
                                        <span class="absolute bottom-full right-0 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('common.view_details') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>