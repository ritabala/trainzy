@extends('layouts.app')

@section('content')
<h2 class="font-semibold text-xl text-gray-800 leading-tight px-4 sm:px-6 lg:px-8 py-4 dark:text-gray-200">
    {{ __('settings.app.title') }}
</h2>

<div class="py-4">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        @livewire('settings.app.business-settings', ['gym' => $gym])
    </div>
</div>
@endsection 