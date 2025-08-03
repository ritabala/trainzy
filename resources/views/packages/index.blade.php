@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center px-4 sm:px-6 lg:px-8 py-4">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('package.management') }}
        </h2>
    </div>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            @livewire('packages.package-management')
        </div>
    </div>
@endsection 