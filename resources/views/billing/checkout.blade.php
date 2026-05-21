@extends('layouts.app')
@section('title', 'Upgrade Plan')

@section('content')
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-8">Upgrade Your Plan</h1>

        @livewire('checkout-component', [
            'team' => $team,
        ])
    </div>
@endsection
