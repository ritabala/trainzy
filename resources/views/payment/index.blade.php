@extends('layouts.app')
@section('content')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8 py-4">
        {{ __('finance.payments.title') }}
    </h2>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            @livewire('payment.payment-management')
        </div>
    </div>
@endsection