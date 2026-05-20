@extends('layouts.app')
@section('title', 'User Profile' . auth()->user()->name)

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-4xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Account Settings</h1>
            <p class="text-sm text-slate-500 mt-1">Manage your public profile settings and security configurations.</p>
        </div>

        <!-- Livewire Components Stack -->
        @livewire('update-profile-form')


    </div>
@endsection
