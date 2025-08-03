<div>
    <div class="bg-white rounded-lg border border-gray-100 dark:bg-gray-800 dark:border-gray-600">
        <div class="p-8">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4 gap-4">
                <div class="flex-1">
                    <div class="relative inline-flex items-center space-x-2">
                        <div class="relative cursor-pointer group" onclick="document.getElementById('datePicker').showPicker()">
                            {{-- Date Input --}}
                            <input 
                                type="text" 
                                value="{{ $selectedDate->format('F j, Y') }}"
                                class="w-40 px-3 py-2 text-sm border border-blue-300 bg-white dark:bg-gray-700 dark:border-blue-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer group-hover:border-blue-300 transition-colors"
                                readonly
                            />
                            
                            <!-- Hidden date input -->
                            <input 
                                type="date" 
                                wire:model.live="selectedDate" 
                                id="datePicker"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                style="z-index: -1;" 
                                max="{{ now()->format('Y-m-d') }}"
                            />
                            
                            {{-- Calendar Icon --}}
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 dark:text-gray-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="flex items-center space-x-4">
                    <button class="text-sm font-medium text-gray-600 border border-gray-600 bg-white dark:text-gray-200 dark:bg-gray-700 dark:border-blue-600 rounded-lg px-4 py-1 hover:text-gray-700 hover:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200" wire:click="openTargetModal">
                        {{ __('body_metrics.set_targets') }}
                    </button>
                    <button class="text-sm font-medium text-blue-600 border border-blue-600 bg-white dark:bg-gray-700 dark:text-white rounded-lg px-4 py-1 hover:text-blue-700 hover:border-blue-700 hover:bg-blue-100 dark:hover:bg-gray-700 dark:hover:text-blue-100 transition-colors duration-200" wire:click="addBodyMetric()">
                        {{ $selectedDate->isToday() ? 'Edit' : 'Update' }}
                    </button>
                </div>
            </div>

            {{-- Title Section --}}
            <div class="mb-6">
                <div class="flex justify-between items-center mb-1">
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ __('body_metrics.title') }}</h1>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{__('body_metrics.sub_title')}}</p>
            </div>

            {{-- All Metrics Section --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mb-6">
                @foreach(collect($metricsData)->sortBy('display_order') as $slug => $metric)
                    <div class="bg-white p-2 rounded-lg border border-gray-100 dark:bg-gray-700 dark:border-gray-600 hover:shadow-md transition-shadow duration-200 cursor-pointer" 
                         wire:click="openMetricDetail('{{ $slug }}')">
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex items-center space-x-1">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-200">{{ $metric['name'] }}</span>
                            </div>
                            <svg class="w-2 h-2 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>  
                        
                        @if(!is_null($metric['latest']))
                            <div class="flex flex-row flex-end justify-between">
                                <div class="text-base font-bold text-gray-900 dark:text-gray-200 mb-0.5">
                                    {{ $metric['latest'] }} <span class="text-sm font-medium text-gray-500 dark:text-gray-200">{{ $metric['unit'] }}</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-200">
                                    {{ __('body_metrics.target_title') }}{{ $metric['target'] ?? 'N/A' }} {{ $metric['target'] ? $metric['unit'] : '' }}
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                @if(!is_null($metric['change']))
                                    <div class="flex items-center text-sm {{ $metric['change'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                        <svg class="w-2 h-2 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $metric['change'] >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                        </svg>
                                        {{ $metric['change'] >= 0 ? '+' : '' }}{{ number_format($metric['change'], 1) }} {{ $metric['unit'] }}
                                    </div>
                                @endif
                                @if(!is_null($metric['target']))
                                    <div class="flex flex-col items-end space-y-1">
                                        @php
                                            $startValue = $this->user->getStartValue($metric['slug']);
                                            $percentage = $this->calculateProgress($startValue, $metric['latest'], $metric['target']);
                                            $strokeColor = $percentage >= 100 ? '#10B981' : ($percentage < 0 ? '#EF4444' : '#3B82F6');
                                            $textColor = $percentage >= 100 ? 'text-green-600' : ($percentage < 0 ? 'text-red-600' : 'text-blue-600');
                                        @endphp
                                        <div class="relative w-9 h-9">
                                            <svg class="w-9 h-9 transform -rotate-90">
                                                <circle
                                                    cx="18"
                                                    cy="18"
                                                    r="16"
                                                    stroke="#E5E7EB"
                                                    stroke-width="2.5"
                                                    fill="none"
                                                />
                                                <circle
                                                    cx="18"
                                                    cy="18"
                                                    r="16"
                                                    stroke="{{ $strokeColor }}"
                                                    stroke-width="2.5"
                                                    fill="none"
                                                    stroke-dasharray="{{ 2 * pi() * 16 }}"
                                                    stroke-dashoffset="{{ 2 * pi() * 16 * (1 - abs($percentage) / 100) }}"
                                                    stroke-linecap="round"
                                                />
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center text-[11px] font-medium {{ $textColor }}">
                                                {{ round($percentage) }}%
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-gray-500 italic text-sm dark:text-gray-400">{{ __('body_metrics.not_added') }}</div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Progress Photos Section --}}
            <div class="bg-white rounded-lg p-4 sm:p-6 mb-6 dark:bg-gray-700 dark:border-gray-600">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200">{{ __('body_metrics.progress_photos') }}</h2>
                        <p class="text-sm text-gray-500 mt-1 dark:text-gray-300">{{__('body_metrics.photo_section_sub_title')}}</p>
                    </div>
                    <div class="flex items-center space-x-4 w-full sm:w-auto">
                        <label for="progress-photo" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer transition-colors duration-200 w-full sm:w-auto justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            {{ __('body_metrics.add_photos') }}
                        </label>
                        <input type="file" id="progress-photo" wire:model="newPhoto" class="hidden" accept="image/*" multiple>
                    </div>
                </div>

                {{-- Photos Grid --}}
                <div class="relative">
                    @if($progressPhotos->isNotEmpty())
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-4">
                            @foreach($progressPhotos as $photo)
                                <div class="group relative aspect-square bg-gray-100 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-200">
                                    {{-- Photo --}}
                                    <img 
                                        src="{{ route('private.files', $photo->file_path) }}" 
                                        alt="Progress photo from {{ $photo->photo_date->format('M d, Y') }}" 
                                        class="w-full h-full object-cover cursor-pointer transform transition-transform duration-300 group-hover:scale-105"
                                        wire:click="$set('selectedPhoto', '{{ $photo->id }}')"
                                    >
                                    
                                    {{-- Overlay with Actions --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-0 left-0 right-0 p-3 flex justify-between items-center">
                                            <span class="text-white text-xs">
                                                {{ $photo->photo_date->format('M d, Y') }}
                                            </span>
                                            <div class="flex items-center space-x-2">
                                                <button 
                                                    wire:click.stop="$set('selectedPhoto', '{{ $photo->id }}')"
                                                    class="p-1.5 rounded-full bg-white/10 hover:bg-white/20 transition-colors duration-200"
                                                    title={{ __('body_metrics.view_photo') }}
                                                >
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button 
                                                    wire:click.stop="deletePhoto({{ $photo->id }})"
                                                    class="p-1.5 rounded-full bg-white/10 hover:bg-red-500/70 transition-colors duration-200"
                                                    title={{ __('body_metrics.delete_photo') }}
                                                >
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-lg p-8">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-200">{{__('body_metrics.no_photos')}}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{__('body_metrics.add_photos_title')}}</p>
                                <div class="mt-6">
                                    <label for="empty-progress-photo" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        {{ __('body_metrics.first_photo1') }}
                                    </label>
                                    <input type="file" id="empty-progress-photo" wire:model="newPhoto" class="hidden" accept="image/*" multiple>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Progress Photos Modal --}}
            <x-modal wire:model="showPhotosModal" maxWidth="2xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">{{__('body_metrics.progress_photos')}}</h3>
                        <button wire:click="closePhotosModal" class="text-gray-400 hover:text-gray-500 dark:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-6">
                        @forelse($progressPhotos as $photo)
                            <div class="relative group">
                                <img 
                                    src="{{ route('private.files', $photo->file_path) }}" 
                                    alt="Progress photo" 
                                    class="w-full h-48 object-cover rounded-lg cursor-pointer"
                                    wire:click="$set('selectedPhoto', '{{ $photo->id }}')"
                                >
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L20 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">{{__('body_metrics.photo_section_sub_title1')}}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{__('body_metrics.first_photo')}}</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="flex justify-center">
                        <label for="modal-progress-photo" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L20 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('body_metrics.add_more_photos') }}
                        </label>
                        <input type="file" id="modal-progress-photo" wire:model="temporaryPhotos" class="hidden" accept="image/*" multiple>
                    </div>
                </div>
            </x-modal>

            {{-- Photo Viewer Modal --}}
            <div x-data="{ show: @entangle('selectedPhoto') }" 
                 x-show="show" 
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-900 opacity-90"></div>
                    </div>

                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                            {{ __('body_metrics.progress_photos') }}
                                        </h3>
                                        <div class="flex items-center space-x-4">
                                            <button @click="show = false" class="text-gray-400 hover:text-gray-500">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        @if($selectedPhoto)
                                            @php
                                                $photo = $progressPhotos->firstWhere('id', $selectedPhoto);
                                                $currentIndex = $progressPhotos->search(function($item) use ($selectedPhoto) {
                                                    return $item->id == $selectedPhoto;
                                                });
                                                $prevPhoto = $progressPhotos->get($currentIndex - 1);
                                                $nextPhoto = $progressPhotos->get($currentIndex + 1);
                                            @endphp
                                            @if($photo)
                                                <div class="relative bg-gray-900 rounded-lg">
                                                    <div class="flex justify-center items-center max-h-[70vh] min-h-[70vh]">
                                                        <img 
                                                            src="{{ route('private.files', $photo->file_path) }}" 
                                                            alt={{ __('body_metrics.progress_photos') }} 
                                                            class="max-w-full max-h-[70vh] object-contain rounded-lg transition-all duration-300"
                                                            x-transition:enter="ease-out duration-300"
                                                            x-transition:enter-start="opacity-0 scale-95"
                                                            x-transition:enter-end="opacity-100 scale-100"
                                                            x-transition:leave="ease-in duration-200"
                                                            x-transition:leave-start="opacity-100 scale-100"
                                                            x-transition:leave-end="opacity-0 scale-95"
                                                        >
                                                    </div>
                                                    
                                                    {{-- Navigation Buttons --}}
                                                    @if($prevPhoto)
                                                        <button 
                                                            wire:click="$set('selectedPhoto', '{{ $prevPhoto->id }}')"
                                                            class="absolute left-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-white/80 hover:bg-white text-gray-800 shadow-md transition-colors duration-200"
                                                        >
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($nextPhoto)
                                                        <button 
                                                            wire:click="$set('selectedPhoto', '{{ $nextPhoto->id }}')"
                                                            class="absolute right-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-white/80 hover:bg-white text-gray-800 shadow-md transition-colors duration-200"
                                                        >
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                        </button>
                                                    @endif

                                                    {{-- Photo Counter --}}
                                                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/80 px-3 py-1 rounded-full text-sm text-gray-800 shadow-md">
                                                        {{ $currentIndex + 1 }} / {{ $progressPhotos->count() }}
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metrics Detail Modal --}}
            <x-dialog-modal wire:model="showMetricDetailModal" maxWidth="2xl">
                <x-slot name="title">
                    {{ __('body_metrics.title') }}
                </x-slot>

                <x-slot name="content">
                    <div class="p-4 sm:p-10">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center space-x-2">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-200">{{ __('body_metrics.title') }}</h3>
                            </div>
                            <button wire:click="addBodyMetric('{{ $selectedMetric }}')" class="text-blue-500 font-medium flex items-center hover:text-blue-700 outline-none transition-colors">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('common.add') }}
                            </button>
                        </div>

                        {{-- Time Period Tabs --}}
                        <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-full p-1 mb-6">
                            @foreach(__('body_metrics.chart_period') as $period)
                                <button 
                                    wire:click="changeTimePeriod('{{ $period }}')" 
                                    class="flex-1 px-4 py-2 text-xs rounded-full {{ $selectedTimePeriod === $period ? 'bg-white shadow-sm text-gray-700 font-bold' : 'text-gray-500 hover:text-gray-700 dark:hover:font-bold dark:text-gray-200' }}"
                                >
                                    {{ $period }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Metrics Change Information --}}
                        <div class="mb-6">
                            <div class="text-gray-500 dark:text-gray-300 text-xs uppercase mb-1">{{__('body_metrics.change')}}</div>
                            <div class="flex items-center">
                                <span class="text-md font-bold {{ $metricChange > 0 ? 'text-green-500' : ($metricChange < 0 ? 'text-red-500' : 'text-gray-900 dark:text-gray-200') }}">
                                    {{ $metricChange !== null ? ($metricChange > 0 ? '+' : '') . number_format($metricChange, 1) : '0.0' }}
                                </span>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-300">{{ $metricUnit }}</span>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                                {{ count($metricHistory) > 0 ? 
                                    (isset($metricHistory[0]['date']) && isset($metricHistory[count($metricHistory) - 1]['date']) ? 
                                        $formattedStartDate . ' - ' . $formattedEndDate : 
                                        __('body_metrics.no_range')) : 
                                    __('body_metrics.no_measurements') }}
                            </div>
                        </div>

                        {{-- Metrics Chart --}}
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-6">
                            <div 
                                class="h-64 relative bg-gray-50 dark:bg-gray-700"
                                x-data="{
                                    chartData: @entangle('metricHistory').live,
                                    chartInstance: null,
                                    noData: false,
                                    isDestroying: false,
                                    targetValue: @entangle('metricTarget').live,
                                    isDarkMode: document.documentElement.classList.contains('dark'),
                                    destroyChart() {
                                        if (this.chartInstance) {
                                            this.isDestroying = true;
                                            try {
                                                this.chartInstance.destroy();
                                            } catch (e) {
                                                console.error('Error destroying chart:', e);
                                            }
                                            this.chartInstance = null;
                                            this.isDestroying = false;
                                        }
                                    },
                                    init() {                                    
                                        this.$watch('chartData', (newValue) => {
                                            if (this.isDestroying) return;                                        
                                            this.$nextTick(() => {
                                                requestAnimationFrame(() => {
                                                    this.renderChart();
                                                });
                                            });
                                        });

                                        // Watch for dark mode changes
                                        this.$watch('isDarkMode', () => {
                                            this.renderChart();
                                        });

                                        // Initial render attempt
                                        this.$nextTick(() => {
                                            if (this.chartData && Array.isArray(this.chartData) && this.chartData.length > 0) {
                                                requestAnimationFrame(() => {
                                                    this.renderChart();
                                                });
                                            } else {
                                                this.noData = true;
                                            }
                                        });
                                    },
                                    renderChart() {
                                        if (this.isDestroying) return;

                                        if (!this.chartData || !Array.isArray(this.chartData) || this.chartData.length === 0) {
                                            this.noData = true;
                                            return;
                                        }

                                        this.noData = false;

                                        // Ensure old chart is destroyed
                                        this.destroyChart();

                                        // Wait for next frame to ensure DOM is ready
                                        requestAnimationFrame(() => {
                                            if (this.isDestroying) return;

                                            const labels = this.chartData.map(item => item?.date ?? '');
                                            const values = this.chartData.map(item => item?.value ?? 0);

                                            const series = [{
                                                name: 'Progress',
                                                data: values
                                            }];

                                            if (this.targetValue !== null) {
                                                series.push({
                                                    name: 'Target',
                                                    data: Array(labels.length).fill(this.targetValue)
                                                });
                                            }

                                            const isDark = this.isDarkMode;
                                            const textColor = isDark ? '#E5E7EB' : '#374151';
                                            const gridColor = isDark ? '#4B5563' : '#E5E7EB';
                                            const tooltipBg = isDark ? '#1F2937' : '#FFFFFF';
                                            const tooltipBorder = isDark ? '#4B5563' : '#E5E7EB';

                                            const options = {
                                                chart: {
                                                    type: 'line',
                                                    height: '100%',
                                                    animations: {
                                                        enabled: true,
                                                        duration: 250
                                                    },
                                                    toolbar: {
                                                        show: false
                                                    },
                                                    zoom: {
                                                        enabled: false
                                                    },
                                                    background: 'transparent',
                                                    foreColor: textColor
                                                },
                                                series: series,
                                                stroke: {
                                                    curve: 'smooth',
                                                    width: [4, 2],
                                                    dashArray: [0, 4]
                                                },
                                                colors: ['#2563eb', '#10B981'],
                                                fill: {
                                                    type: ['solid', 'none'],
                                                    colors: ['#2563eb', '#10B981']
                                                },
                                                markers: {
                                                    size: [5, 0],
                                                    colors: ['#2563eb', '#10B981'],
                                                    strokeColors: isDark ? '#1F2937' : '#FFFFFF',
                                                    strokeWidth: 2,
                                                    hover: {
                                                        size: 6
                                                    }
                                                },
                                                xaxis: {
                                                    categories: labels,
                                                    labels: {
                                                        rotate: 0,
                                                        maxHeight: 60,
                                                        style: {
                                                            fontSize: '12px',
                                                            colors: textColor
                                                        }
                                                    },
                                                    tickAmount: 10,
                                                    axisBorder: {
                                                        show: true,
                                                        color: gridColor
                                                    },
                                                    axisTicks: {
                                                        show: true,
                                                        color: gridColor
                                                    }
                                                },
                                                yaxis: {
                                                    labels: {
                                                        formatter: function(value) {
                                                            return value + ' {{ $metricUnit }}';
                                                        },
                                                        style: {
                                                            colors: textColor
                                                        }
                                                    },
                                                    axisBorder: {
                                                        show: true,
                                                        color: gridColor
                                                    },
                                                    axisTicks: {
                                                        show: true,
                                                        color: gridColor
                                                    }
                                                },
                                                legend: {
                                                    position: 'top',
                                                    horizontalAlign: 'center',
                                                    offsetY: 0,
                                                    markers: {
                                                        width: 10,
                                                        height: 10,
                                                        radius: 0
                                                    },
                                                    itemMargin: {
                                                        horizontal: 20
                                                    },
                                                    fontSize: '12px',
                                                    labels: {
                                                        colors: textColor
                                                    }
                                                },
                                                tooltip: {
                                                    theme: isDark ? 'dark' : 'light',
                                                    y: {
                                                        formatter: function(value) {
                                                            return value + ' {{ $metricUnit }}';
                                                        }
                                                    },
                                                    style: {
                                                        fontSize: '12px'
                                                    }
                                                },
                                                grid: {
                                                    borderColor: gridColor,
                                                    strokeDashArray: 4,
                                                    padding: {
                                                        top: 20,
                                                        right: 20,
                                                        bottom: 20,
                                                        left: 20
                                                    }
                                                }
                                            };

                                            this.chartInstance = new ApexCharts(this.$refs.chartCanvas, options);
                                            this.chartInstance.render();
                                        });
                                    }
                                }"
                                x-on:hidden.window="destroyChart()"
                                x-on:beforeunload.window="destroyChart()"
                                x-on:dark-mode-changed.window="isDarkMode = $event.detail"
                                wire:ignore
                            >
                                <div x-ref="chartCanvas" class="w-full h-full"></div>

                                <div x-show="noData" class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-700">
                                    <p class="text-gray-500 dark:text-gray-400">{{__('body_metrics.no_data')}}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Body Measurement Type Selection --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-3 text-sm">
                            @foreach($metricTypes as $metricType)
                                <button 
                                    wire:click="openMetricDetail('{{ $metricType->slug }}')" 
                                    class="p-2 border rounded-lg text-center {{ $selectedMetric === $metricType->slug ? 'border-blue-500 bg-blue-50 dark:bg-blue-600 dark:text-white' : 'border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 dark:text-white' }}"
                                >
                                    {{ $metricType->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </x-slot>
                <x-slot name="footer">
                    <x-button wire:click="closeMetricDetailModal">
                        {{ __('common.close') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </div> 
    </div>

    {{-- Add Body Metrics Modal --}}
    <x-modal wire:model="showAddMetricsModal" maxWidth="2xl">
        <div class="p-4 sm:p-10">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-2">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-200">{{__('body_metrics.body_progress')}}</h3>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-200 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-200">{{ $selectedDate->format('F j, Y') }}</span>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 dark:text-gray-200 mb-4 text-sm">{{ __('body_metrics.add_metric_title') }}</p>
            </div>

            <form wire:submit.prevent="saveBodyMeasurements">
                <div class="space-y-6 mb-6">
                    {{-- Selected metrics inputs --}}
                    <div class="{{ !$selectedMetric ? 'grid grid-cols-1 sm:grid-cols-2 gap-4' : '' }}">
                        @foreach($metricsForForm as $metric)
                            <div class="flex-1">
                                <label for="metric-{{ $metric->slug }}" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                    {{ $metric->name }}
                                </label>
                                <div class="flex">
                                    <input
                                        type="number"
                                        step="0.01"
                                        id="metric-{{ $metric->slug }}"
                                        wire:model.lazy="addMetricsValues.{{ $metric->slug }}"
                                        class="block w-full rounded-l-md {{ $metric->unit ? "" : 'rounded-r-md' }} font-medium text-sm border-gray-300 shadow-sm focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600"
                                        placeholder="{{ __('body_metrics.enter') }} {{ $metric->name }}""
                                    >
                                    @if($metric->unit)
                                        <div class="inline-flex border-l-0 items-center text-sm justify-center px-4 border border-gray-300 bg-gray-100 text-gray-500 rounded-r-md dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                                            {{ $metric->unit }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Add photos section - Only show when not from chart modal --}}
                    @if(!$selectedMetric)
                        <div class="mt-8">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200">{{__('body_metrics.add_progress_photos')}}</h4>
                            </div>

                            <div class="flex justify-center">
                                <label for="add-metric-photo" class="w-full">
                                    <div class="border-2 border-gray-300 border-dashed rounded-lg p-6 text-center cursor-pointer hover:border-gray-400 dark:border-gray-600 flex flex-col items-center justify-center">
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 mb-4 w-full">
                                            {{-- Show existing photos --}}
                                            @foreach($progressPhotos as $photo)
                                                <div class="relative group">
                                                    <img src="{{ route('private.files', $photo->file_path) }}" class="h-25 w-25 object-cover rounded">
                                                    <div class="absolute top-0 left-0 h-25 w-25 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity duration-200 rounded flex items-center justify-center">
                                                        <button wire:click.prevent="markPhotoForDeletion({{ $photo->id }})" 
                                                                class="opacity-0 group-hover:opacity-100 text-white p-2 hover:bg-red-500 rounded-full transition-opacity duration-200">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach

                                            {{-- Show temporary photos --}}
                                            @if($temporaryPhotos)
                                                @foreach($temporaryPhotos as $index => $photo)
                                                    <div class="relative group">
                                                        <img src="{{ $photo->temporaryUrl() }}" class="h-25 w-25 object-cover rounded">
                                                        <div class="absolute top-0 left-0 h-25 w-25 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity duration-200 rounded flex items-center justify-center">
                                                            <button wire:click.prevent="removeTemporaryPhoto({{ $index }})" 
                                                                    class="opacity-0 group-hover:opacity-100 text-white p-2 hover:bg-red-500 rounded-full transition-opacity duration-200">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <p class="text-blue-500 text-sm hover:text-blue-600 transition-colors">{{ __('body_metrics.choose_file') }}</p>
                                    </div>
                                </label>
                                <input type="file" id="add-metric-photo" wire:model="temporaryPhotos" class="hidden" accept="image/*" multiple>
                            </div>
                        </div>

                        {{-- Notes section - Only show when not from chart modal --}}
                        <div class="mt-8">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{ __('body_metrics.notes') }}</label>
                            <textarea 
                                id="notes" 
                                wire:model.lazy="addNotes" 
                                rows="3" 
                                class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder:text-sm placeholder:text-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600"
                                placeholder="{{ __('body_metrics.notes_pl') }}""
                            ></textarea>
                        </div>
                    @endif
                </div>

                <div class="mt-8 flex space-x-4">
                    <button
                        type="button"
                        wire:click="closeAddMetricsModal"
                        class="w-full py-2 px-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg focus:outline-none"
                    >
                        {{ __('common.cancel') }}
                    </button>
                    <button
                        type="submit"
                        class="w-full py-2 px-2 text-sm bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg focus:outline-none"
                    >
                        {{ __('common.update') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Target Values Modal --}}
    <x-modal wire:model="showTargetModal" maxWidth="2xl">
        <div class="p-4 sm:p-10">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-2">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-200">{{ __('body_metrics.set_target_values') }}</h3>
                </div>
                <button wire:click="closeTargetModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 mb-4 text-sm dark:text-gray-200">{{ __('body_metrics.target_modal_title') }}</p>
            </div>

            <form wire:submit.prevent="saveTargetValues">
                <div class="space-y-6 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($metricTypes as $metricType)
                        <div class="flex items-end space-x-4">
                            <div class="flex-1">
                                <label for="target-{{ $metricType->slug }}" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                                    {{ $metricType->name }}
                                </label>
                                <div class="flex">
                                    <input
                                        type="number"
                                        step="0.01"
                                        id="target-{{ $metricType->slug }}"
                                        wire:model.lazy="targetValues.{{ $metricType->slug }}"
                                        class="block w-full rounded-l-md font-medium text-sm border-gray-300 shadow-sm focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600"
                                        placeholder="{{ __('body_metrics.enter_target') . $metricType->name }}""
                                    >
                                    <div class="inline-flex border-l-0 items-center text-sm justify-center px-4 border border-gray-300 bg-gray-100 text-gray-500 rounded-r-md dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ $metricType->unit }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex space-x-4">
                    <button
                        type="button"
                        wire:click="closeTargetModal"
                        class="w-full py-2 px-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg focus:outline-none dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                    >
                        {{ __('common.cancel') }}
                    </button>
                    <button
                        type="submit"
                        class="w-full py-2 px-2 text-sm bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg focus:outline-none"
                    >
                        {{ __('body_metrics.save_targets_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>


    <link rel="stylesheet" href="{{ asset('vendor/apexcharts/apexcharts.css') }}">

    <script src="{{ asset('vendor/apexcharts/apexcharts.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Function to dispatch dark mode change event
        const dispatchDarkModeChange = () => {
            const isDark = document.documentElement.classList.contains('dark');
            window.dispatchEvent(new CustomEvent('dark-mode-changed', {
                detail: isDark
            }));
        };

        // Watch for dark mode changes
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    dispatchDarkModeChange();
                }
            });
        });

        // Start observing the html element for class changes
        observer.observe(document.documentElement, {
            attributes: true
        });

        // Initial dispatch
        dispatchDarkModeChange();
    });
    </script>

</div>

