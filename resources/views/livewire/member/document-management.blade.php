<div>
    <div class="px-2 sm:px-4 py-3 sm:py-5 border border-gray-100 dark:border-gray-600 rounded-lg">
        <div class="flex justify-end mb-3 sm:mb-4">
            <button wire:click="openUploadModal" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-1.5 sm:mr-2 h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span class="hidden sm:inline">{{ __('members.upload_doc') }}</span>
                <span class="sm:hidden">{{ __('members.upload') }}</span>
            </button>
        </div>

        @if (!$documents || $documents->isEmpty() || count($documents) === 0)
            <div class="text-center py-8 sm:py-12">
                <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('members.no_docs') }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('members.zero_uploads') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-3 rounded-lg p-3 sm:p-6">
                @foreach ($documents as $document)
                    <div class="relative rounded-lg border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 px-4 sm:px-6 py-4 sm:py-5 shadow-sm hover:border-gray-400">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 max-w-[120px] sm:max-w-[150px] truncate dark:text-gray-200" title="{{ $document->name }}">{{ $document->name }}</h4>
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ ucwords(str_replace('_', ' ', $document->type)) }}</p>
                                <p class="text-xs text-gray-400 mt-1 dark:text-gray-400">{{ number_format($document->file_size / 1024, 2) }} {{__('members.kb')}}</p>
                            </div>
                            <div class="ml-2 sm:ml-4 flex-shrink-0 flex space-x-1 sm:space-x-2">
                                <button wire:click="downloadDocument({{ $document->id }})" class="text-blue-600 hover:text-blue-900" title="Download">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button wire:click="deleteDocument({{ $document->id }})" class="text-red-600 hover:text-red-900" title="Delete">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Upload Modal -->
    @if($showUploadModal)
        <x-dialog-modal wire:model="showUploadModal">
            <x-slot name="title">
                {{ __('members.upload_doc') }}
            </x-slot>

            <x-slot name="content">
                <form wire:submit.prevent="uploadDocument" id="upload-form">
                    <div class="mt-4">
                        <label for="documentType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('members.doc_type') }}</label>
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="inline-flex mt-1 dark:bg-gray-700 dark:text-gray-200 items-center justify-between w-full px-3 sm:px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    <span class="truncate">{{ $documentType ? ucwords(str_replace('_', ' ', $documentType)) : __('members.select_type') }}</span>
                                    <svg class="h-5 w-5 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="dark:border-gray-600 dark:text-gray-200">
                                    @foreach($this->documentTypes as $value => $label)
                                        <x-dropdown-link wire:click="$set('documentType', '{{ $value }}')" :selected="$this->documentType == $value">
                                            {{ $label }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                        @error('documentType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4">
                        <label for="documentName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('members.doc_name') }}</label>
                        <input type="text" wire:model.defer="documentName" id="documentName" class="mt-1 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                        @error('documentName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4">
                        <label for="documentFile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('members.file') }}</label>
                        <input type="file" wire:model.lazy="documentFile" id="documentFile" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-50 block w-full shadow-sm sm:text-sm border border-gray-300 dark:border-gray-600 rounded-md">
                        @error('documentFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </form>
            </x-slot>

            <x-slot name="footer">
                <button type="submit" form="upload-form" class="inline-flex w-full justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('members.upload') }}
                </button>
                <button type="button" wire:click="closeUploadModal" class="mt-3 inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('common.cancel') }}
                </button>
            </x-slot>
        </x-dialog-modal>
    @endif
</div> 