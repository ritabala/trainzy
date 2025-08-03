@extends('layouts.home')

@section('content')
    @livewire('gym.gym-detail', ['slug' => $slug])
@endsection 