@extends('layouts.app')

@section('content')
<h2 class="font-semibold text-xl text-gray-800 leading-tight px-4 sm:px-6 lg:px-8 py-4 dark:text-gray-200">
    {{ __('body_metrics.manage_body_metrics') }}
</h2>

<div class="py-4">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        @livewire('settings.body-metric.body-metric-type-management')
    </div>
</div>
@endsection 