@extends('layouts.app')
@section('title', 'Team Members')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b mb-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

                    <!-- Title and Context -->
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                            Team Members
                        </h1>
                        <p class="text-gray-500 max-w-2xl mt-1 leading-relaxed">
                            Manage your collaborators and roles for <strong>{{ $team->name }}</strong>.
                        </p>
                    </div>

                    <!-- Action Button -->
                    @if (auth()->user()->isTeamAdmin($team))
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <a href="{{ route('team.invite-form', $team) }}"
                                class="flex-1 md:flex-none text-center px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                                <span class="mr-1">+</span> Invite Member
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Members Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Role</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Joined</th>
                            @if (auth()->user()->isTeamAdmin($team))
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($members as $member)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $member->name }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $member->email }}</td>
                                <td class="px-6 py-4">
                                    @if (auth()->user()->isTeamAdmin($team))
                                        <form method="POST"
                                            action="{{ route('team.update-member-role', [$team, $member]) }}"
                                            class="inline">
                                            @csrf
                                            <select name="role" onchange="this.form.submit()"
                                                class="border rounded px-2 py-1 text-sm">
                                                <option value="member"
                                                    {{ $member->pivot->role === 'member' ? 'selected' : '' }}>Member
                                                </option>
                                                <option value="manager"
                                                    {{ $member->pivot->role === 'manager' ? 'selected' : '' }}>Manager
                                                </option>
                                                <option value="admin"
                                                    {{ $member->pivot->role === 'admin' ? 'selected' : '' }}>Admin
                                                </option>
                                            </select>
                                        </form>
                                    @else
                                        <span
                                            class="inline-block px-3 py-1 bg-gray-100 rounded text-sm">{{ ucfirst($member->pivot->role) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 text-sm">
                                    {{ \Carbon\Carbon::parse($member->pivot->joined_at)->format('M d, Y') }}
                                </td>
                                @if (auth()->user()->isTeamAdmin($team))
                                    <td class="px-6 py-4">
                                        @if ($team->user_id !== $member->id)
                                            <form method="POST"
                                                action="{{ route('team.remove-member', [$team, $member]) }}" class="inline"
                                                onsubmit="return confirm('Remove this member?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                                            </form>
                                        @else
                                            <span class="text-gray-500 text-sm">Owner</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $members->links() }}
        </div>
    </div>
@endsection
