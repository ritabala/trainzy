<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    {{-- Filters --}}
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="search" 
                class="w-full  py-1.5 text-gray-700 dark:text-gray-300 dark:bg-gray-800 placeholder:text-gray-500 text-sm pl-10 rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300"
                placeholder="{{ __('package.search_gym_or_package') }}">
        </div>

        <!-- Payment Status Filter -->
        <div class="w-full">
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:text-white dark:bg-gray-800 dark:border-gray-700">
                        <span>
                            {{ $paymentStatuses[$paymentStatus] ?? __('common.all_status') }}
                        </span>
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="py-1 dark:border dark:border-gray-700">
                        @foreach($paymentStatuses as $value => $label)
                            <x-dropdown-link wire:click="$set('paymentStatus', '{{ $value }}')" :selected="$paymentStatus === '{{ $value }}'">
                                {{ $label }}
                            </x-dropdown-link>
                        @endforeach
                    </div>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Payment Gateway Filter -->
        <div class="w-full">
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:text-white dark:bg-gray-800 dark:border-gray-700">
                        <span>
                            {{ $paymentGateways[$paymentGateway] ?? __('common.all_gateways') }}
                        </span>
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="py-1 dark:border dark:border-gray-700">
                        @foreach($paymentGateways as $value => $label)
                            <x-dropdown-link wire:click="$set('paymentGateway', '{{ $value }}')" :selected="$paymentGateway === '{{ $value }}'">
                                {{ $label }}
                            </x-dropdown-link>
                        @endforeach
                    </div>
                </x-slot>
            </x-dropdown>
        </div>
    </div>

    {{-- Table --}}
    <div>
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                @foreach([
                                    'gym_id' => 'Gym',
                                    'package_id' => 'Package',
                                    'amount' => 'Amount',
                                    'payment_gateway' => 'Payment Gateway',
                                    'payment_status' => 'Status',
                                    'transaction_id' => 'Transaction ID',
                                    'paid_on' => 'Paid On'
                                ] as $key => $label)
                                    <th wire:click="sortBy('{{ $key }}')" 
                                        class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider {{ !in_array($key, $nonSortableFields) ? 'cursor-pointer' : '' }}">
                                        {{ $label }}
                                        @if($sortField === $key)
                                            <span class="ml-1">
                                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                                            </span>
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-xs sm:text-sm dark:text-gray-300">
                                    <td class="px-2 sm:px-4 py-3 sm:py-6">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 sm:h-10 sm:w-10">
                                                <img src="{{ $payment->gym->logo_url }}" alt="Gym Logo" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                            </div>
                                            <div class="ml-2 sm:ml-4">
                                                <div class="text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->gym->name }}</div>
                                                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $payment->gym->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs sm:text-sm font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $payment->package->package_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap">
                                        ${{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap capitalize">
                                        {{ $payment->payment_gateway }}
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs sm:text-sm font-medium rounded-full 
                                            @if($payment->payment_status === 'completed')
                                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($payment->payment_status === 'pending')
                                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($payment->payment_status === 'failed')
                                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else
                                                bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            @endif">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 break-all">
                                        {{ $payment->transaction_id }}
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap">
                                        {{ $payment->paid_on ? $payment->paid_on->format('M d, Y H:i') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('package.no_payments_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</div>
