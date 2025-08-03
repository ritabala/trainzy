<div class="space-y-6">
    @if($purchaseHistory->count() > 0)
        <div class="overflow-x-auto bg-white rounded-lg mt-6 shadow dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">
                            {{ __('settings.billing.package') }}
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">
                            {{ __('settings.billing.billing_cycle') }}
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">
                            {{ __('settings.billing.amount') }}
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">
                            {{ __('settings.billing.date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">
                            {{ __('settings.billing.payment_gateway') }}
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">
                            {{ __('settings.billing.transaction_id') }}
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">
                            {{ __('settings.billing.status') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($purchaseHistory as $subscription)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-sm" wire:key="subscription-{{ $subscription->id . '-' . $loop->index }}">
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $subscription->package->package_name }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap capitalize">
                                {{ $subscription->billing_cycle }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ global_currency_format($subscription->amount, $subscription->package->currency_id) ?? '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $subscription->starts_on->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($subscription->packagePayment)
                                    {{ ucfirst($subscription->packagePayment->payment_gateway) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 break-all">
                                @if($subscription->packagePayment)
                                    {{ $subscription->packagePayment->transaction_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $subscription->is_active ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}">
                                    {{ $subscription->is_active ? __('settings.billing.active') : __('settings.billing.expired') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400">{{ __('settings.billing.no_purchase_history') }}</p>
        </div>
    @endif
</div> 