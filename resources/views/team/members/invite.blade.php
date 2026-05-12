@extends('layouts.app')
@section('title', 'Invite Member')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold mb-8">Invite Member</h1>

            <form method="POST" action="{{ route('team.invite-member', $team) }}"
                class="bg-white rounded-lg shadow p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" placeholder="member@example.com" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="member">Member (View tasks, cannot manage)</option>
                        <option value="manager">Manager (Create/edit tasks, cannot manage members)</option>
                        <option value="admin">Admin (Full access)</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                        Send Invitation
                    </button>
                </div>
            </form>

            <p class="mt-6 text-gray-600 text-sm">
                The user must have a TaskFlow account. They'll be notified to accept the invitation.
            </p>
        </div>
    </div>
@endsection
