@extends('layouts.app')
@section('content')
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            @livewire('staff.view-staff-details', ['userId' => $userId])
        </div>
    </div>
@endsection 