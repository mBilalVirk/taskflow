@php
    $team = auth()->user()->currentTeam();
    // \Log::debug('Current Team: ' . ($team ? $team->id : 'No team found'));
@endphp
<div class="grid md:grid-cols-3 gap-8">
    @foreach ($this->plans() as $plan => $details)
        <div
            class="bg-white rounded-lg shadow-lg p-8 flex flex-col h-full {{ $plan === 'pro' ? 'ring-2 ring-blue-500 transform scale-105' : '' }}">
            <!-- Badge -->
            @if ($plan === 'pro')
                <div class="mb-4">
                    <span class="bg-blue-500 text-white px-4 py-1 rounded-full text-sm font-bold">
                        Most Popular
                    </span>
                </div>
            @endif

            <!-- Plan Name -->
            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $details['name'] }}</h3>

            <!-- Price -->
            <div class="mb-6">
                @if ($details['price'] > 0)
                    <span class="text-5xl font-bold text-gray-900">${{ $details['price'] / 100 }}</span>
                    <span class="text-gray-600">/month</span>
                @else
                    <span class="text-5xl font-bold text-gray-900">Free</span>
                @endif
            </div>

            <!-- Features -->
            <ul class="space-y-4 mb-8 flex-1">
                @foreach ($details['features'] as $feature)
                    <li class="flex items-center gap-3">
                        <i class="fas fa-check text-green-500"></i>
                        <span class="text-gray-700">{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>

            <!-- CTA Button -->
            @auth
                @if (auth()->user()->isCurrentTeam && auth()->user()->isCurrentTeam->subscription?->plan === $plan)
                    <button disabled
                        class="w-full px-6 py-3 bg-gray-400 text-white rounded-lg font-bold cursor-not-allowed">
                        Current Plan
                    </button>
                @elseif($plan === 'free')
                    <a href="{{ route('billing.dashboard', ['team' => $team ? $team->id : null]) }}"
                        class="w-full px-6 py-3 bg-gray-200 text-gray-900 rounded-lg font-bold text-center hover:bg-gray-300 transition">
                        Manage Plan
                    </a>
                @else
                    <button wire:click="selectPlan('{{ $plan }}')"
                        class="w-full px-6 py-3 {{ $plan === 'pro' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-900 hover:bg-black' }} text-white rounded-lg font-bold transition">
                        Choose {{ $details['name'] }}
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}"
                    class="w-full px-6 py-3 bg-blue-500 text-white rounded-lg font-bold text-center hover:bg-blue-600 transition">
                    Sign In to Upgrade
                </a>
            @endauth
        </div>
    @endforeach
</div>

@script
    <script>
        Livewire.on('planSelected', ({
            plan
        }) => {
            window.location.href =
                '{{ route('billing.checkout', ['team' => $team ? $team->id : null, 'plan' => '']) }}'
                .replace('""', `"${plan}"`);
        });
    </script>
@endscript
