<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="mt-4 sm:mt-0">
            <div class="w-full sm:w-64 mb-4 sm:mb-0 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="search" 
                    placeholder="{{ __('settings.payment_gateways.offline.search_requests') }}" 
                    class="w-full py-1.5 text-gray-700 dark:bg-gray-700 placeholder:text-gray-500 dark:text-gray-200 dark:border-gray-600 dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 dark:hover:border-blue-500 text-sm pl-10 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200 hover:border-blue-300"
                >
            </div>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg mt-6 shadow dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th wire:click="sortBy('created_at')" 
                        class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider cursor-pointer whitespace-normal break-words">
                        {{ __('settings.billing.request_date') }}
                        @if($sortField === 'created_at')
                            <span class="ml-1">
                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                            </span>
                        @endif
                    </th>
                    <th wire:click="sortBy('package_id')" 
                        class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider cursor-pointer whitespace-normal break-words">
                        {{ __('settings.billing.package_name') }}
                        @if($sortField === 'package_id')
                            <span class="ml-1">
                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                            </span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider whitespace-normal break-words">
                        {{ __('settings.billing.billing_cycle') }}
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider whitespace-normal break-words">
                        {{ __('settings.payment_gateways.offline.payment_method') }}
                    </th>
                    <th wire:click="sortBy('amount')" 
                        class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider cursor-pointer whitespace-normal break-words">
                        {{ __('settings.billing.amount') }}
                        @if($sortField === 'amount')
                            <span class="ml-1">
                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                            </span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider whitespace-normal break-words">
                        {{ __('settings.billing.status') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($requests as $request)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-sm" wire:key="request-{{ $request->id . '-' . $loop->index }}">
                        <td class="px-4 py-3 whitespace-normal break-words">
                            {{ $request->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-4 py-3 whitespace-normal break-words">
                            {{ $request->package->package_name }}
                        </td>
                        <td class="px-4 py-3 whitespace-normal break-words capitalize">
                            {{ $request->billing_cycle }}
                        </td>
                        <td class="px-4 py-3 whitespace-normal break-words">
                            {{ $request->paymentGateway->offline_method_name }}
                        </td>
                        <td class="px-4 py-3 whitespace-normal break-words">
                            {{ global_currency_format($request->amount, $request->package->currency_id) ?? '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-normal break-words">
                            @if($request->is_accepted)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                    {{ __('settings.billing.status_approved') }}
                                </span>
                            @elseif($request->is_accepted === false && $request->accepted_at !== null)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                    {{ __('settings.billing.status_rejected') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">
                                    {{ __('settings.billing.status_pending') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                            {{ __('settings.billing.no_offline_requests') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div> 