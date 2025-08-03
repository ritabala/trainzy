@extends('layouts.app')

@section('content')


<h2 class="font-semibold text-xl text-gray-800 leading-tight px-4 sm:px-6 lg:px-8 py-4 dark:text-gray-200">
    {{ __('settings.billing.title') }}
</h2>

<div class="max-w-full mx-auto sm:px-6 lg:px-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))   
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
</div>


<div class="py-4">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        @livewire('settings.billing.billing-settings')
    </div>
</div>
@endsection 