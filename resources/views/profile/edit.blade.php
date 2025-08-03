@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center px-4 py-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('header.profile') }}
        </h2>
    </div>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            @livewire('profile')
        </div>
    </div>
@endsection 