@extends('layouts.app')

@section('title', 'Edit Project - ' . $project->name)

@section('content')
    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-3xl font-bold mb-2">Edit Project</h1>
            <p class="text-gray-600 mb-8">Team: <strong>{{ $team->name }}</strong></p>

            <form method="POST" action="{{ route('projects.update', [$team, $project]) }}">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Project Name</label>
                    <input type="text" name="name" value="{{ old('name', $project->name) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="5"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $project->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" value="{{ old('color', $project->color) }}"
                            class="w-12 h-12 p-1 border border-gray-300 rounded-lg cursor-pointer">
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('projects.show', [$team, $project]) }}"
                        class="px-6 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition">
                        Update Project
                    </button>
                </div>
            </form>

            <!-- Delete Button -->
            <form method="POST" action="{{ route('projects.destroy', [$team, $project]) }}" class="mt-8 pt-6 border-t"
                onsubmit="return confirm('Are you sure you want to delete this project?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                    Delete Project
                </button>
            </form>
        </div>
    </div>
@endsection
