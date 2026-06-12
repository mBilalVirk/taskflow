@extends('layouts.app')

@section('title', 'Create New Project - ' . $team->name)

@section('content')
    <div class="max-w-2xl mx-auto mt-8 px-4 sm:px-0">
        <div class="bg-white shadow-md rounded-lg p-8 dark:bg-dark-card">
            <h1 class="text-3xl font-bold mb-2 text-gray-900 dark:text-gray-400">Create New Project</h1>
            <p class="text-gray-600 mb-8 dark:text-gray-400">Team: <strong
                    class="text-gray-900 dark:text-white">{{ $team->name }}</strong></p>

            <form method="POST" action="{{ route('projects.store', $team) }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-400">Project Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border border-gray-300 bg-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:bg-dark-bg dark:border-gray-700 dark:text-gray-300 dark:focus:ring-blue-600"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-400">Description</label>
                    <textarea name="description" rows="5"
                        class="w-full border border-gray-300 bg-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:bg-dark-bg dark:border-gray-700 dark:text-gray-300 dark:focus:ring-blue-600">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-400">Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" value="{{ old('color', '#3B82F6') }}"
                            class="w-12 h-12 p-1 border border-gray-300 rounded-lg cursor-pointer dark:bg-dark-bg dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Choose project accent color</span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('projects.index', $team) }}"
                        class="px-6 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition dark:text-gray-400 dark:hover:bg-dark-hover">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition dark:bg-blue-500 dark:hover:bg-blue-600">
                        Create Project
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
