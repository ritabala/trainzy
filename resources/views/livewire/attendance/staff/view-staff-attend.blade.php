<div>
    <div class="bg-white shadow-xl rounded-lg overflow-hidden dark:bg-gray-800">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __('members.attendance.view_staff') }}
            </h3>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Staff Info -->
            <div class="mb-6">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($attendance->user->profile_photo_path && Storage::disk('public')->exists($attendance->user->profile_photo_path))
                            <img src="{{ Storage::url($attendance->user->profile_photo_path) }}" alt="{{ $attendance->user->name }}" 
                                 class="h-12 w-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                        @elseif($attendance->user->gender)
                            <img src="{{ asset('images/' . $attendance->user->gender . '.svg') }}" alt="{{ $attendance->user->name }}" 
                                 class="h-12 w-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                        @else
                            <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center text-xl font-bold border border-gray-200 dark:border-gray-600">
                                {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $attendance->user->name }}
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $attendance->user->email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Attendance Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Check-in Time -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('members.attendance.check_in') }}
                            </h5>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $attendance->check_in_at->format('Y-m-d g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Notes -->
            @if($attendance->notes)
            <div class="mt-6">
                <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                    {{ __('members.attendance.notes') }}
                </h5>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-gray-900 dark:text-gray-100">
                        {{ $attendance->notes }}
                    </p>
                </div>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
            <div class="flex justify-end">
                <a href="{{ route('attendance.staff.index') }}" 
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </div>
</div> 