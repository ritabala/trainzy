@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center px-4 sm:px-6 lg:px-8 py-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('staff.create_staff_type') }}
        </h2>
        <a href="{{ route('settings.staff_types.index') }}" 
            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            {{ __('common.back_to_list') }}
        </a>
    </div>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            @livewire('settings.staff-type.create-edit-staff-type')
        </div>
    </div>
@endsection 