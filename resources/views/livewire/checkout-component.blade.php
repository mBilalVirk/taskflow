<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-8 text-white">
            <h1 class="text-3xl font-bold">Complete Your Upgrade</h1>
            <p class="text-blue-100 mt-2">
                You're subscribing to <span class="font-semibold">{{ ucfirst($this->plan) }}</span>
            </p>
        </div>

        <div class="p-8">
            <!-- Plan Summary -->
            <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $this->planDetails['name'] ?? ucfirst($this->plan) }} Plan
                        </h3>
                        <p class="text-gray-600 mt-1">{{ $this->planDetails['description'] ?? '' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-5xl font-bold text-gray-900">
                            ${{ ($this->planDetails['price'] ?? 2900) / 100 }}
                        </span>
                        <span class="text-gray-500 text-sm">/month</span>
                    </div>
                </div>
            </div>

            <!-- Features -->
            @if (!empty($this->planDetails['features']))
                <div class="mb-8">
                    <h4 class="font-semibold text-gray-700 mb-3">What's included:</h4>
                    <ul class="space-y-2">
                        @foreach ($this->planDetails['features'] as $feature)
                            <li class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-check text-green-500"></i>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Status Message -->
            @if ($this->status)
                <div
                    class="mb-6 p-4 rounded-xl {{ str_contains($this->status, 'error') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                    {{ $this->status }}
                </div>
            @endif

            <!-- Checkout Button -->
            <button wire:click="checkout" wire:loading.attr="disabled"
                class="w-full py-5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-lg rounded-2xl transition-all flex items-center justify-center gap-3 shadow-lg">

                <span wire:loading.remove>
                    Pay ${{ ($this->planDetails['price'] ?? 2900) / 100 }} / month and Upgrade
                </span>

                <span wire:loading class="flex items-center gap-3">
                    <i class="fas fa-spinner fa-spin"></i>
                    Redirecting to Secure Checkout...
                </span>
            </button>

            <p class="text-center text-xs text-gray-500 mt-6">
                🔒 Secure checkout powered by <strong>Stripe</strong><br>
                You can cancel your subscription anytime.
            </p>
        </div>
    </div>
</div>
