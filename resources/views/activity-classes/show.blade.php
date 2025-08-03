@extends('layouts.app')
@section('content')
    @livewire('activity-class.time-slot-management', ['activityClass' => $activityClass])
@endsection 