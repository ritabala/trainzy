<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-2 rounded-md mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search and Create Role Section -->
    <div class="flex flex-col sm:flex-row justify-between items-center pb-7 border-b border-gray-200 dark:border-gray-700">
        <div class="w-full sm:w-64 mb-4 sm:mb-0 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="search" 
                   class="w-full py-1.5 text-gray-700 dark:text-gray-300 placeholder:text-gray-500 dark:placeholder:text-gray-400 text-sm pl-10 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 dark:focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:hover:border-indigo-500 dark:bg-gray-700" 
                   placeholder="{{ __('user.search_roles') }}">
        </div>
    </div>

    <!-- Roles Table -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg mt-6 shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-300 tracking-wider">{{ __('user.role_name') }}</th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-300 tracking-wider">{{ __('user.permissions') }}</th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-300 tracking-wider">{{ __('user.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($roles as $role)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                        <td class="px-4 py-3">
                            <div class="relative group capitalize" x-data="{ isTruncated: false }" x-init="isTruncated = $el.querySelector('.truncate').scrollWidth > $el.querySelector('.truncate').clientWidth">
                                <div class="truncate text-gray-900 dark:text-gray-100">
                                    {{ $role->display_name ?? $role->name }}
                                </div>
                                <template x-if="isTruncated">
                                    <div class="absolute bottom-full left-0 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 mb-1">
                                        {{ $role->display_name ?? $role->name }}
                                        <div class="absolute -bottom-1 left-4 w-2 h-2 bg-gray-900 dark:bg-gray-800 transform rotate-45"></div>
                                    </div>
                                </template>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($role->permissions as $permission)
                                    <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                        {{ $permission->display_name ?? $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2 justify-evenly">
                                <button wire:click="editRole({{ $role->id }})" 
                                        class="text-gray-600 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-500 relative group">
                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                        {{ __('user.edit') }}
                                    </span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{ show: @entangle('showModal') }"
         x-show="show" 
         @click.away="$wire.set('showModal', false)"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
         <div class="flex items-center justify-center min-h-screen px-4 py-8 text-center sm:flex sm:items-center sm:justify-center">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $roleId ? __('user.edit_role') : __('user.create_role') }}
                    </h3>
                </div>
                <form wire:submit.prevent="saveRole">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('user.role_name') }}</label>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">{{ $displayName }}</h3>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('user.permissions') }}</label>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                @foreach($availablePermissions as $permission)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="permissions" value="{{ $permission->name }}"
                                               class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-500 shadow-sm focus:border-indigo-300 dark:focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $permission->display_name ?? $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('permissions') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-base font-medium text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('common.save') }}
                        </button>
                        <button type="button" wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('common.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
