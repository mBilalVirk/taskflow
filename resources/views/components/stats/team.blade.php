<!-- Team Members Overview -->
<div class="bg-white rounded-lg shadow p-6 dark:bg-dark-card">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-400">Team Members</h3>
        <a href="{{ route('team.members', $team) }}" class="text-blue-500 hover:text-blue-700 text-sm font-semibold">
            Manage Team →
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $members = $team->members()->take(8)->get();
        @endphp

        @forelse($members as $member)
            <div class="border rounded-lg p-4 text-center hover:shadow-lg transition dark:border-gray-400">
                {{-- <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg mx-auto mb-3">
                    {{ substr($member->name, 0, 1) }}
                </div> --}}
                <img src="{{ 'storage/' . $member->avatar ?? 'https://ui-avatars.com/api/?background=random&name=' . urlencode($task->assignee->name) }}"
                    alt="{{ $member->name }}"
                    class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg mx-auto mb"
                    title="{{ $member->name }}">
                <h4 class="font-semibold text-gray-900 text-sm dark:text-gray-400">{{ $member->name }}</h4>
                <p class="text-xs text-gray-500 mb-3">{{ $member->email }}</p>
                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                    {{ ucfirst($member->pivot->role) }}
                </span>
            </div>
        @empty
            <div class="col-span-full text-center py-8 dark:bg-dark-card">
                <p class="text-gray-500 mb-4">No team members yet</p>
                <a href="{{ route('team.invite-form', $team) }}"
                    class="text-blue-500 hover:text-blue-700 text-sm font-semibold">
                    Invite your first member →
                </a>
            </div>
        @endforelse
    </div>

    @if ($team->canAddMembers())
        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200 text-center">
            <p class="text-sm text-gray-700 mb-3">
                You can add <strong>{{ $team->members_limit - $team->members()->count() }}</strong> more member(s)
                to your plan
            </p>
            <a href="{{ route('team.invite-form', $team) }}"
                class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm font-semibold">
                Invite Member
            </a>
        </div>
    @else
        <div class="mt-4 p-4 bg-orange-50 rounded-lg border border-orange-200 text-center">
            <p class="text-sm text-gray-700 mb-3">
                You've reached the member limit for your plan
            </p>
            <a href="#"
                class="inline-block bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 text-sm font-semibold">
                Upgrade Plan
            </a>
        </div>
    @endif
</div>
