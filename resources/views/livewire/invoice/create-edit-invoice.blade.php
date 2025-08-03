<div>
    <div class="bg-white shadow-xl rounded-lg overflow-hidden dark:bg-gray-800">
        <!-- Invoice Information -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="invoice_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.invoices.number') }}</label>
                    <div class="flex">
                        <input type="text" disabled wire:model="invoice.invoice_prefix" id="invoice_prefix" placeholder="Prefix" 
                            class="w-1/3 text-sm rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        <input type="text" wire:model="invoice.invoice_number" id="invoice_number" placeholder="Number" 
                            class="w-2/3 text-sm rounded-r-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    </div>
                    @error('invoice.invoice_number') <span class="text-red-500 text-xs mt-o">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="invoice_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.invoices.invoice_date') }}</label>
                    <input type="date" wire:model="invoice.invoice_date" id="invoice_date" 
                        class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    @error('invoice.invoice_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('finance.invoices.due_date') }}</label>
                    <input type="date" wire:model="invoice.due_date" id="due_date" 
                        class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    @error('invoice.due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Member Information -->
        <div class="p-6 bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:border-gray-600">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{__('finance.invoices.member_info')}}</h2>
            <div class="grid grid-cols-1 gap-6">
                <!-- User Selection Column -->
                @if($fromAdd)
                <div class="max-w-[250px]">
                    <div class="relative" x-data="{ open: false }">
                        <div class="relative">
                            <button type="button" @click="open = !open"
                                class="w-full text-left text-sm rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                @if($member)
                                    <span class="text-gray-900 dark:text-gray-200 truncate block">{{ $member->name }}</span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('finance.invoices.select_member') }}</span>
                                @endif
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>
                        </div>

                        <div x-show="open" @click.away="open = false"
                            class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-300 py-1 dark:bg-gray-700 dark:border-gray-600">
                            <!-- Search input -->
                            <div class="px-3 py-2">
                                <input type="text" 
                                    wire:model.live.debounce.300ms="userSearchQuery"
                                    placeholder="{{ __('finance.invoices.search_member') }}"
                                    class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            </div>

                            <!-- Loading indicator -->
                            <div wire:loading wire:target="userSearchQuery" class="px-3 py-2">
                                <div class="flex items-center justify-center">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                                </div>
                            </div>

                            <!-- Members list -->
                            <div class="max-h-60 overflow-y-auto" 
                                x-intersect="$wire.loadMoreUsers()"
                                x-intersect:enter="$wire.loadMoreUsers()">
                                @if($users->isEmpty())
                                    <div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('finance.invoices.no_member') }}
                                    </div>
                                @else
                                    @foreach($users as $user)
                                        <button type="button" 
                                            wire:click="$set('selectedUserId', {{ $user->id }})"
                                            @click="open = false"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"
                                            :class="{'bg-gray-50 dark:bg-gray-600': {{ $selectedUserId }} === {{ $user->id }}}">
                                            <div class="font-medium text-gray-900 dark:text-gray-200 truncate">{{ $user->name }}</div>
                                            <div class="text-gray-500 dark:text-gray-400 text-xs truncate">{{ $user->email }}</div>
                                        </button>
                                    @endforeach

                                    <!-- Loading more indicator -->
                                    @if($hasMoreUsers)
                                        <div class="px-3 py-2 text-center">
                                            <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @error('selectedUserId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                <!-- Member Details Column -->
                <div class="{{ !$fromAdd ? 'md:col-span-2' : '' }}">
                @if($member)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm min-h-[100px] flex items-center">
                        <div class="flex flex-wrap items-center gap-4">
                            <!-- Member Name -->
                            <div class="flex items-center">
                                <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-full mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{__('finance.invoices.member_name')}}</p>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm font-medium">{{ $member->name }}</p>
                                </div>
                            </div>

                            <!-- Member Email -->
                            <div class="flex items-center">
                                <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-full mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{__('finance.invoices.email')}}</p>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ $member->email }}</p>
                                </div>
                            </div>

                            @if($userMembership)
                            <!-- Membership Details -->
                            <div class="flex items-center">
                                <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-full mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('finance.invoices.active_membership') }}</p>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ $userMembership->membership->name }} ({{ $userMembership->membershipFrequency->frequency->name }})</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm flex items-center justify-center min-h-[100px]">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 dark:text-gray-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('finance.invoices.select_member_info') }}</p>
                        </div>
                    </div>
                @endif
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('finance.invoices.items') }}</h2>
                
                <div class="relative">
                    <x-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                {{ __('finance.invoices.add_prod') }}
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 dark:border dark:border-gray-600" @click.stop>
                                <div class="mb-2">
                                    <input 
                                        type="text" 
                                        wire:model.live="productSearchQuery" 
                                        placeholder="{{ __('finance.invoices.search_prod') }}" 
                                        @click.stop
                                        class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                </div>
                                <div class="max-h-60 overflow-y-auto">
                                    @if(!empty($filteredProducts) && count($filteredProducts) > 0)
                                        @foreach($filteredProducts as $product)
                                            <x-dropdown-link 
                                                :selected="$selectedProductId === $product->id"
                                                wire:click="addProduct({{ $product->id }})"
                                                @click="$root.dispatchEvent(new CustomEvent('close'))"
                                                class="text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                            >
                                                {{ $product->name }}
                                            </x-dropdown-link>
                                        @endforeach
                                    @else
                                        <div class="py-2 px-4 text-sm text-gray-500 dark:text-gray-400">{{ __('finance.invoices.no_prod') }}</div>
                                    @endif
                                </div>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($invoiceDetails as $index => $item)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm hover:shadow-md transition-shadow duration-200 relative">
                    @if($item['type'] !== 'membership')
                    <button type="button" wire:click="removeItem({{ $index }})" 
                        class="absolute -top-3 -right-3 bg-white dark:bg-gray-700 rounded-full p-1 shadow-md border border-gray-300 dark:border-gray-600 text-red-500 hover:text-red-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    @endif
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 p-4">
                        <!-- Description Column -->
                        <div class="lg:col-span-5 border-r border-gray-200 dark:border-gray-600 pr-4">
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('finance.invoices.item_description') }}</label>
                            <div class="flex flex-col mb-2">
                                <input type="text" wire:model="invoiceDetails.{{ $index }}.name" placeholder="Item Name" 
                                    class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mb-1 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    @if($item['type'] === 'membership') disabled @endif>
                                @error("invoiceDetails.{$index}.name") <span class="text-red-500 text-xs mt-0">* {{ $message }}</span> @enderror
                            </div>
                            <textarea wire:model="invoiceDetails.{{ $index }}.description" rows="2" placeholder="{{ __('finance.invoices.desc_plc') }}"
                                class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"></textarea>
                        </div>
                        
                        <!-- Quantity Column -->
                        <div class="lg:col-span-1 border-r border-gray-200 dark:border-gray-600 pr-4">
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{__('finance.invoices.quantity')}}</label>
                            <input type="number" wire:model="invoiceDetails.{{ $index }}.quantity" wire:change="calculateAmount({{ $index }})" min="1" step="1"
                                class="w-full max-w-[120px] mx-auto text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                style="appearance: textfield; -moz-appearance: textfield; -webkit-appearance: textfield;"
                                @if($item['type'] === 'membership') disabled @endif>
                            @error("invoiceDetails.{$index}.quantity") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Unit Price Column -->
                        <div class="lg:col-span-2 border-r border-gray-200 dark:border-gray-600 pr-4">
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('finance.invoices.unit_price') }}</label>
                            <input type="number" wire:model="invoiceDetails.{{ $index }}.unit_price" wire:change="calculateAmount({{ $index }})" step="0.01" 
                                class="w-full max-w-[150px] mx-auto text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                style="appearance: textfield; -moz-appearance: textfield; -webkit-appearance: textfield;">
                            @error("invoiceDetails.{$index}.unit_price") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Tax Column -->
                        <div class="relative lg:col-span-3 border-r border-gray-200 dark:border-gray-600 pr-4" x-data="{ open: false }" @click.away="open = false">
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{__('finance.invoices.tax')}}</label>
                        
                            <div @click="open = !open" 
                                class="w-full text-sm rounded-md border border-gray-300 dark:border-gray-600 shadow-sm p-2 cursor-pointer bg-white dark:bg-gray-700 flex items-center justify-between">
                                @if(!empty($item['selected_taxes']) && count($item['selected_taxes']) > 0)
                                    <span class="truncate dark:text-gray-300">
                                        @foreach($taxes->whereIn('id', $item['selected_taxes']) as $selectedTax)
                                            {{ $selectedTax->tax_name }}: {{ $selectedTax->tax_percent }}%{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-300">{{ __('finance.invoices.select_tax') }}</span>
                                @endif
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        
                            <div x-show="open" x-cloak class="absolute z-10 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md mt-1 shadow-lg">
                                @if($taxes->count() > 0)
                                <ul class="max-h-40 overflow-y-auto py-1">
                                    @foreach($taxes as $tax)
                                        <li wire:click="toggleTax({{ $index }}, {{ $tax->id }})" 
                                            class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center">
                                            <input type="checkbox" 
                                                class="mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600" 
                                                @if(in_array($tax->id, $item['selected_taxes'] ?? [])) checked @endif>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $tax->tax_name }}: {{ $tax->tax_percent }}%</span>
                                        </li>
                                    @endforeach
                                </ul>
                                @else
                                    <div class="px-3 py-2 text-gray-500 dark:text-gray-400 text-sm">{{ __('finance.invoices.no_tax') }}</div>
                                @endif
                            </div>

                            @if(!empty($item['selected_taxes']) && count($item['selected_taxes']) > 0)
                                <div class="flex flex-wrap gap-1 my-2">
                                    @foreach($taxes->whereIn('id', $item['selected_taxes']) as $selectedTax)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                            {{ $selectedTax->tax_name }}: {{ $selectedTax->tax_percent }}%
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Amount Column -->
                        <div class="lg:col-span-1 text-right">
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('finance.invoices.amount') }}</label>
                            <div class="flex justify-end">
                                <span wire:text="invoiceDetails.{{ $index }}.amount"
                                    class="inline-block font-medium text-sm dark:text-gray-300">
                                    {{ sprintf("%.2f", $item['amount'] ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-6 flex justify-start">
                <button type="button" wire:click="addItem" 
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 dark:text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ __('finance.invoices.add_custom') }}
                </button>
            </div>
        </div>

        <!-- Invoice Summary -->
        <div class="p-6 bg-gray-50 dark:bg-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('finance.invoices.notes') }}</h3>
                    <textarea wire:model="invoice.notes" id="notes" rows="4" placeholder="{{ __('finance.invoices.notes_plc') }}" 
                        class="w-full placeholder:text-gray-400 dark:placeholder:text-gray-500 text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"></textarea>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('finance.invoices.invoice_summary') }}</h3>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-600">
                        <!-- Subtotal Row -->
                        <div class="grid grid-cols-2 border-b border-gray-100 dark:border-gray-600">
                            <div class="py-3.5 px-4 text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('finance.invoices.sub_tot') }}</div>
                            <div class="py-3.5 px-4 text-right text-sm font-semibold dark:text-gray-300">{{ sprintf("%.2f", $invoice['sub_total']) }}</div>
                        </div>
                        
                        <!-- Discount Row -->
                        <div class="grid grid-cols-2 border-b border-gray-100 dark:border-gray-600">
                            <div class="py-3.5 px-4 text-gray-600 dark:text-gray-400 text-sm font-medium">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="whitespace-nowrap mb-2 sm:mb-0">{{ __('finance.invoices.disc') }}</span>
                                    <div class="flex items-center gap-2 sm:ml-2">
                                        <x-dropdown align="left">
                                            <x-slot name="trigger">
                                                <button type="button" class="inline-flex justify-between w-[55px] items-center h-[30px] px-2 border border-gray-300 dark:border-gray-600 text-xs leading-4 font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-400">
                                                    {{ $invoice['discount_type'] === '%' ? '%' : __('finance.invoices.amt') }}
                                                    <svg class="ml-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <div class="py-1">
                                                    <button type="button" wire:click="updateDiscountType('%')" 
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 {{ $invoice['discount_type'] === '%' ? 'bg-gray-50 dark:bg-gray-600 font-medium' : '' }}">
                                                        {{ __('finance.invoices.perc') }} (%)
                                                    </button>
                                                    <button type="button" wire:click="updateDiscountType('fixed')" 
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 {{ $invoice['discount_type'] === 'fixed' ? 'bg-gray-50 dark:bg-gray-600 font-medium' : '' }}">
                                                        {{ __('finance.invoices.fixed_amt') }}
                                                    </button>
                                                </div>
                                            </x-slot>
                                        </x-dropdown>
                                        
                                        <input type="number" wire:model.live="invoice.discount_value" wire:change="calculateDiscount" step="0.01" min="0" 
                                            class="w-[85px] text-xs text-center h-[30px] rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300"
                                            style="appearance: textfield; -moz-appearance: textfield; -webkit-appearance: textfield;">
                                            @error('invoice.discount_value') <span class="text-red-500 text-xs ">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="py-3.5 px-4 text-right text-sm font-semibold dark:text-gray-300">
                                @if($invoice['discount_amount'] < 0)
                                    <span class="text-red-600 dark:text-red-400">-{{ sprintf("%.2f", abs($invoice['discount_amount'])) }}</span>
                                @else
                                    {{ sprintf("%.2f", $invoice['discount_amount']) }}
                                @endif
                                @if($invoice['discount_amount'] > $invoice['sub_total'])
                                    <span class="text-red-400 dark:text-red-300 text-xs text-right font-normal"><br>{{ __('finance.invoices.disc_err') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Tax Section -->
                        <div>
                            <!-- Tax Total Row -->
                            <div class="grid grid-cols-2 border-b border-gray-100 dark:border-gray-600">
                                <div class="py-3.5 px-4 text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('finance.invoices.tax') }}</div>
                                <div class="py-3.5 px-4 text-right text-sm font-semibold dark:text-gray-300">{{ sprintf("%.2f", $totalTaxAmount) }}</div>
                            </div>
                            
                            <!-- Tax Breakdown -->
                            @if(count($taxSummary) > 0)
                                @foreach($taxSummary as $taxId => $taxData)
                                    <div class="grid grid-cols-2 border-b border-gray-50 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                        <div class="py-2 pl-6 pr-4 text-gray-500 dark:text-gray-400 text-xs">{{ $taxData['name'] }}:{{ $taxData['rate'] }}%</div>
                                        <div class="py-2 px-4 text-right text-gray-500 dark:text-gray-400 text-xs">{{ sprintf("%.2f", $taxData['amount']) }}</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <!-- Total Row -->
                        <div class="grid grid-cols-2 bg-blue-50 dark:bg-blue-900 border-t border-blue-100 dark:border-blue-800">
                            <div class="py-4 px-4 text-blue-800 dark:text-blue-200 text-sm font-bold">Total</div>
                            <div class="py-4 px-4 text-right text-blue-800 dark:text-blue-200 font-bold text-base">{{ sprintf("%.2f", $invoice['total_amount']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:click="togglePaymentReceived" class="form-checkbox h-4 w-4 text-blue-600 dark:text-blue-500" @checked($paymentReceived)>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('finance.payments.payment_received') }}</span>
                    </label>
                </div>

                @if($paymentReceived)
                    <div class="mt-4 space-y-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <!-- Due Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('finance.invoices.due')}}</label>
                                <input type="number" value="{{ sprintf("%.2f", $invoice['total_amount'] - (float)($paymentAmount ?: 0)) }}" disabled
                                    class="mt-1 block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:text-gray-300"
                                    style="appearance: textfield; -moz-appearance: textfield; -webkit-appearance: textfield;">
                            </div>
                            
                            <!-- Amount Received -->
                            <div>
                                <label for="paymentAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('finance.payments.amt_received') }}</label>
                                <input type="number" wire:model.live="paymentAmount" id="paymentAmount" step="0.01" min="0" max="{{ $invoice['total_amount'] }}"
                                    class="mt-1 block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300"
                                    style="appearance: textfield; -moz-appearance: textfield; -webkit-appearance: textfield;">
                            </div>

                            <!-- Payment Mode -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('finance.payments.mode') }}</label>
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
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="cancel" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        {{ __('common.cancel') }}
                    </button>
                    <button type="button" wire:click="save" 
                        class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-sm text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ $editMode ? __('finance.invoices.update') : __('finance.invoices.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
