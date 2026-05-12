@extends('layouts.app')
@section('title', 'Create New Team')

@section('content')
    <div class="min-h-screen bg-slate-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-sm border border-slate-200">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">Create your team</h2>
                <p class="mt-2 text-center text-sm text-slate-600">
                    Start managing projects and collaborating with others.
                </p>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('team.store') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Team Name</label>
                        <input id="name" name="name" type="text" required
                            class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="e.g. Marketing Team or Alpha Squad">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description
                            (Optional)</label>
                        <textarea id="description" name="description" rows="3"
                            class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="What does this team do?"></textarea>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Team
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
