<div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-3 flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-3 sm:space-y-0">
            <div class="flex items-center">
                @if($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path))
                    <img src="{{ Storage::url($user->profile_photo_path) }}" class="h-12 w-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                @elseif($user->gender)
                    <img src="{{ asset('images/' . $user->gender . '.svg') }}" class="h-12 w-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                @else
                    <div class="h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                        <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif
                <div class="ml-3">
                    <h2 class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $fullName }}</h2>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center text-sm">
                        <span class="text-gray-500 dark:text-gray-400">{{ $user->staffDetail?->staffType?->name ?? __('staff.member') }}</span>
                        <span class="hidden sm:inline mx-2 text-gray-400 dark:text-gray-500">•</span>
                        <span class="text-gray-500 dark:text-gray-400 sm:ml-1">{{ __('staff.since') }} {{ $user->created_at->timezone(gym()->timezone)->format('M Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-2.5 py-0.5 inline-flex text-sm font-medium rounded {{ $user->is_active == '1' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700' }}">
                    {{ $user->is_active == '1' ? __('common.active') : __('common.inactive') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Basic Information Section -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="px-3 py-2">
            <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('members.basic_info') }}</h3>
        </div>
        <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.email')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->email ?? '-' }}</dd>
            </div>
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.phone')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->phone_number ?? '-' }}</dd>
            </div>
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.date_of_birth')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->date_of_birth ?? '-' }}</dd>
            </div>
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('members.gender') }}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->gender ? ucfirst($user->gender) : '-' }}</dd>
            </div>
        </dl>
    </div>

    <!-- Address Section -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="px-3 py-2">
            <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{__('members.address')}}</h3>
        </div>
        <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.street_address')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->address ?? '-' }}</dd>
            </div>
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.city')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->city ?? '-' }}</dd>
            </div>
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('members.state')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->state ?? '-' }}</dd>
            </div>
        </dl>
    </div>

    <!-- Professional Details Section -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="px-3 py-2">
            <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('staff.prof_details') }}</h3>
        </div>
        <dl class="mt-3 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="px-3 py-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('staff.join_date')}}</dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->staffDetail?->date_of_joining ? $user->staffDetail?->date_of_joining->format('M d, Y') : '-' }}</dd>
                </div>
                <div class="px-3 py-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('staff.blood') }}</dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->staffDetail?->blood_group ?? '-' }}</dd>
                </div>
                <div class="px-3 py-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('staff.certifications') }}</dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->staffDetail?->specialization ?? '-' }}</dd>
                </div>
            </div>
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('staff.certifications')}}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100">
                    @if($user->staffDetail?->certifications)
                        <ul class="list-disc ml-4 space-y-1">
                            @foreach($user->staffDetail?->certifications as $certification)
                                <li>{{ trim($certification) }}</li>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </dd>
            </div>
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('staff.med_history') }}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $user->staffDetail?->medical_history ?? '-' }}</dd>
            </div>
        </dl>
    </div>

    <!-- Emergency Contact Section -->
    <div class="p-4">
        <div class="px-3 py-2">
            <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('members.emergency_contact') }}</h3>
        </div>
        <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('members.emergency_contact_name') }}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->emergency_contact_name ?? '-' }}</dd>
            </div>
            <div class="px-3 py-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('members.emergency_contact_phone') }}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user->emergency_contact_phone ?? '-' }}</dd>
            </div>
        </dl>
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

    <!-- Last Updated Section -->
    @if($user->staffDetail?->updated_at)
        <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400 text-right">
            {{ __('staff.last_updated') }} {{ $user->staffDetail?->updated_at->format('M d, Y h:i A') }}
        </div>
    @endif
</div> 