@extends('layouts.app')
@section('title', 'Project Analytics -' . $project->name)

@section('content')
    <div class="p-6">
        @livewire('project-analytics', ['team' => $team, 'project' => $project])
    </div>
    <div class="mt-8 p-6">
        @livewire('activity-timeline', ['team' => $team, 'project' => $project])
    </div>
@endsection
