@extends('layouts.app')
@section('content')
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            @livewire('attendance.staff.view-staff-attend', ['id' => $id])
        </div>
    </div>
@endsection 