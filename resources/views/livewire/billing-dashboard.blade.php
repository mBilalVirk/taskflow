<div class="space-y-8">
    <!-- Current Plan Card -->
    <div class="bg-white rounded-lg shadow p-8">
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Left Side -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    {{ ucfirst($this->subscriptionStatus()['plan']) }} Plan
                </h3>

                <div class="space-y-3 mb-6">
                    <p class="text-gray-600">
                        <span class="font-bold">Price:</span>
                        {{ $this->subscriptionStatus()['price'] }}/month
                    </p>
                    <p class="text-gray-600">
                        <span class="font-bold">Status:</span>
                        <span
                            class="px-3 py-1 rounded-full text-sm font-bold {{ $this->subscriptionStatus()['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($this->subscriptionStatus()['status']) }}
                        </span>
                    </p>
                    <p class="text-gray-600">
                        <span class="font-bold">Team Members:</span>
                        {{ $this->subscriptionStatus()['current_members'] }}
                        @if ($this->subscriptionStatus()['members_limit'])
                            / {{ $this->subscriptionStatus()['members_limit'] }}
                        @else
                            (unlimited)
                        @endif
                    </p>
                    @if ($this->subscriptionStatus()['renews_at'])
                        <p class="text-gray-600">
                            <span class="font-bold">Renews:</span>
                            {{ $this->subscriptionStatus()['renews_at']->format('M d, Y') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Right Side - Actions -->
            <div class="space-y-3">
                @if (!$this->subscriptionStatus()['is_free'])
                    <button wire:click="upgradeToPro" @if ($this->subscriptionStatus()['plan'] === 'pro') disabled @endif
                        class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition @if ($this->subscriptionStatus()['plan'] === 'pro') opacity-50 cursor-not-allowed @endif">
                        Upgrade to Pro
                    </button>

                    <button wire:click="upgradeToEnterprise" @if ($this->subscriptionStatus()['plan'] === 'enterprise') disabled @endif
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition @if ($this->subscriptionStatus()['plan'] === 'enterprise') opacity-50 cursor-not-allowed @endif">
                        Upgrade to Enterprise
                    </button>
                @else
                    <a href="{{ route('billing.checkout', ['team' => $team, 'plan' => 'pro']) }}"
                        class="block w-full px-4 py-2 bg-blue-500 text-white rounded-lg text-center hover:bg-blue-600 transition">
                        Upgrade to Pro
                    </a>
                @endif

                @if ($this->subscriptionStatus()['is_active'] && !$this->subscriptionStatus()['is_free'])
                    <button wire:click="cancelSubscription"
                        wire:confirm="Are you sure you want to cancel your subscription?"
                        class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        Cancel Subscription
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Plan Features -->
    <div class="bg-white rounded-lg shadow p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Plan Features</h3>
        <ul class="space-y-2">
            @foreach ($this->availablePlans()[$this->subscriptionStatus()['plan']]['features'] as $feature)
                <li class="flex items-center gap-3 text-gray-700">
                    <i class="fas fa-check text-green-500"></i>
                    {{ $feature }}
                </li>
            @endforeach
        </ul>
    </div>
</div>
