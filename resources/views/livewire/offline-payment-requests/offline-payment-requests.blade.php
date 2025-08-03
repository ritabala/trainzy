<div>
    <div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-7 border-b border-gray-200 dark:border-gray-700">
            <!-- Search -->
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

        <!-- Table -->
        <div class="overflow-x-auto bg-white rounded-lg mt-6 shadow dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        @foreach([
                            'created_at' => __('settings.payment_gateways.offline.request_date'),
                            'gym.name' => __('settings.payment_gateways.offline.gym_name'),
                            'package.name' => __('settings.payment_gateways.offline.package_name'),
                            'billing_cycle' => __('settings.billing.billing_cycle'),
                            'paymentGateway.offline_method_name' => __('settings.payment_gateways.offline.payment_method'),
                            'amount' => __('settings.payment_gateways.offline.amount'),
                            'is_accepted' => __('settings.payment_gateways.offline.status'),
                        ] as $key => $label)
                            <th wire:click="sortBy('{{ $key }}')" 
                                class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider whitespace-normal break-words {{ !in_array($key, $nonSortableFields ?? []) ? 'cursor-pointer' : '' }}">
                                {{ $label }}
                                @if($sortField === $key)
                                    <span class="ml-1">
                                        {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                                    </span>
                                @endif
                            </th>
                        @endforeach
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider whitespace-normal break-words">{{ __('settings.payment_gateways.offline.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($requests as $request)
                        <tr class="whitespace-nowrap">
                            <td class="px-6 py-4 whitespace-normal break-words text-sm text-gray-900 dark:text-gray-100">
                                {{ $request->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal break-words text-sm text-gray-900 dark:text-gray-100">
                                {{ $request->gym->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal break-words text-sm text-gray-900 dark:text-gray-100">
                                {{ $request->package->package_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal break-words text-sm text-gray-900 dark:text-gray-100">
                                {{ $request->billing_cycle }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal break-words text-sm text-gray-900 dark:text-gray-100">
                                {{ $request->paymentGateway->offline_method_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal break-words text-sm text-gray-900 dark:text-gray-100">
                                {{ global_currency_format($request->amount, $request->package->currency_id) ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal break-words text-sm">
                                @if($request->is_accepted)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ __('settings.billing.status_approved') }}
                                    </span>
                                @elseif($request->is_accepted === false && $request->accepted_at !== null)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        {{ __('settings.billing.status_rejected') }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        {{ __('settings.billing.status_pending') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-normal break-words text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-3">
                                @if($request->is_accepted === null || ($request->is_accepted === false && $request->accepted_at === null))
                                    <button wire:click="handleApprove({{ $request->id }})" 
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 relative group"
                                            title="{{ __('settings.payment_gateways.offline.approve_tooltip') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('settings.payment_gateways.offline.approve_tooltip') }}
                                        </span>
                                    </button>
                                    <button wire:click="handleReject({{ $request->id }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 relative group"
                                            title="{{ __('settings.payment_gateways.offline.reject_tooltip') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('settings.payment_gateways.offline.reject_tooltip') }}
                                        </span>
                                    </button>
                                    <a href="{{ Storage::url($request->document_path) }}" 
                                        download
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 relative group"
                                        title="{{ __('settings.payment_gateways.offline.download_receipt') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('settings.payment_gateways.offline.download_receipt') }}
                                        </span>
                                    </a>
                                @elseif($request->is_accepted || ($request->is_accepted === false && $request->accepted_at !== null))
                                    <a href="{{ Storage::url($request->document_path) }}" 
                                        download
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 relative group"
                                        title="{{ __('settings.payment_gateways.offline.download_receipt') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('settings.payment_gateways.offline.download_receipt') }}
                                        </span>
                                    </a>
                                @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                {{ __('settings.payment_gateways.offline.no_requests_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>
</div> 