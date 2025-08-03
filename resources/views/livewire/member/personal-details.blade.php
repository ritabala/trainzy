<div class="space-y-8">
    <div class="flex items-center space-x-6">
        <div class="inline-block">
            @if($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path))
                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" 
                     class="w-20 h-20 rounded-full object-cover border border-gray-200 dark:border-gray-600">
            @elseif($user->gender)
                <img src="{{ asset('images/' . $user->gender . '.svg') }}" alt="{{ $user->name }}" 
                     class="w-20 h-20 rounded-full object-cover border border-gray-200 dark:border-gray-600">
            @else
                <div class="w-20 h-20 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center text-3xl font-bold border border-gray-200 dark:border-gray-600">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-200">{{__('members.personal_info')}}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('members.member_since') }}{{ $user->created_at->timezone(gym()->timezone)->format('F Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 border border-gray-100 rounded-lg p-6 gap-6 dark:border-gray-700 dark:bg-gray-800">
        <div class="space-y-6">
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.full_name')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $user->name ?? '-' }}</dd>
            </div>
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.email')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $user->email ?? '-' }}</dd>
            </div>
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.phone')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $user->phone_number ?? '-' }}</dd>
            </div>
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.date_of_birth')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $user->date_of_birth ?? '-' }}</dd>
            </div>
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.gender')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $user->gender ? ucfirst($user->gender) : '-' }}</dd>
            </div>
        </div>

        <div class="space-y-6">
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.address')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $user->address ?? '-' }}</dd>
            </div>
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.city')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $user->city ?? '-' }}</dd>
            </div>
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.state')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $user->state ?? '-' }}</dd>
            </div>
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.emergency_contact_name')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $user->emergency_contact_name ?? '-' }}</dd>
            </div>
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.emergency_contact_phone')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $user->emergency_contact_phone ?? '-' }}</dd>
            </div>
        </div>
    </div>

    <!-- QR Code Section -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 border border-gray-100 rounded-lg p-6 gap-6 dark:border-gray-700 dark:bg-gray-800">
        <div class="space-y-6">
            <div class="space-y-1">
                <dt class="text-sm font-bold text-gray-900 dark:text-gray-100">{{__('attendance.qr_code')}}</dt>
                <dd class="mt-2">
                    <div class="space-y-2">
                        <img src="data:image/png;base64,{{ base64_encode($user->qr_code) }}" alt="QR Code" class="w-48 h-48">
                        @if(auth()->user()->getCachedPermissions()->contains('download_qr_code'))
                            <button wire:click="downloadQrCode({{ $user->id }})" 
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900 hover:bg-green-200 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                {{ __('attendance.download') }}
                            </button>
                        @endif
                    </div>
                </dd>
            </div>
        </div>
        <div class="space-y-6">
            <div class="space-y-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('attendance.scan_instructions')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-200">
                    {{__('attendance.scan_instructions_text')}}
                </dd>
            </div>
        </div>
    </div>
</div>
