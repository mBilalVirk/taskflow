@extends('layouts.app')
@section('title', 'Team Analytics' . $team->name)

@section('content')
    @livewire('team-analytics-dashboard', ['team' => $team])
@endsection
