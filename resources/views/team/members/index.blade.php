@extends('layouts.app')
@section('title', 'Team Members')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Team Members</h1>
                @if (auth()->user()->isTeamAdmin($team))
                    <a href="{{ route('team.invite-form', $team) }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        + Invite Member
                    </a>
                @endif
            </div>

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
                                                    {{ $member->pivot->role === 'admin' ? 'selected' : '' }}>Admin</option>
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
