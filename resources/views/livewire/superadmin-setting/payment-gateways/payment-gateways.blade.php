<div class="">
    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="switchTab('stripe')" 
                    class="px-3 py-2 text-sm font-medium {{ $activeTab === 'stripe' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                {{ __('settings.payment_gateways.stripe.title') }}
            </button>
            <button wire:click="switchTab('offline')"
                    class="px-3 py-2 text-sm font-medium {{ $activeTab === 'offline' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                {{ __('settings.payment_gateways.offline.title') }}
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="mt-6">
        @if($activeTab === 'stripe')
            @livewire('superadmin-setting.payment-gateways.stripe-settings')    
        @else
            @livewire('superadmin-setting.payment-gateways.offline-payment-settings')
        @endif
    </div>
</div> 