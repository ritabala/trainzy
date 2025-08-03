<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-end mb-4">
        <button wire:click="downloadPayment" class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 dark:border-gray-600 bg-blue-500 dark:bg-blue-600 shadow-sm text-sm font-medium rounded-lg text-white hover:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            {{ __('finance.invoices.download_pdf') }}
        </button>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Header with amount -->
        <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('finance.invoices.total_amount') }}</p>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ currency_format($payment->amount_paid) }}</h2>
                </div>
                @php
                    $statusStyles = [
                        'completed' => [
                            'bg' => 'bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800',
                            'text' => 'text-green-700 dark:text-green-400'
                        ],
                        'pending' => [
                            'bg' => 'bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800',
                            'text' => 'text-yellow-700 dark:text-yellow-400'
                        ],
                        'failed' => [
                            'bg' => 'bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800',
                            'text' => 'text-red-700 dark:text-red-400'
                        ],
                        'partially_paid' => [
                            'bg' => 'bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800',
                            'text' => 'text-blue-700 dark:text-blue-400'
                        ],
                        'cancelled' => [
                            'bg' => 'bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600',
                            'text' => 'text-gray-700 dark:text-gray-400'
                        ],
                    ];

                    $styles = $statusStyles[$payment->status] ?? [
                        'bg' => 'bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600',
                        'text' => 'text-gray-600 dark:text-gray-400'
                    ];
                @endphp

                <div class="flex items-center rounded-lg px-4 py-2 capitalize {{ $styles['bg'] }}">
                    <span class="text-xs font-medium {{ $styles['text'] }}">
                        {{ Str::replace('_', ' ', $payment->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="p-4 sm:p-6 space-y-4">
             <!-- Client --> 
             <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('finance.payments.client') }}</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->user->name ?? '--' }}</p>
                </div>
            </div>

            <!-- Payment Date -->
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('finance.payments.date') }}</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->payment_date->format('d M, Y H:i:s') }}</p>
                </div>
            </div>

            <!-- Invoice Number -->
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('finance.invoices.number') }}</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->invoice->invoice_number ?? '--' }}</p>
                </div>
            </div>

            <!-- Transaction ID -->
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('finance.payments.transaction_id') }}</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->transaction_no ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Payment Mode -->
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('finance.payments.mode') }}</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ Str::replace('_', ' ', $payment->payment_mode) }}</p>
                </div>
            </div>

            <!-- Remark -->
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('finance.payments.remarks') }}</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->remark ?? '--' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
