<section id="pricing" class="py-20 px-6 bg-dark-card/20" data-aos="slide-up">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 gradient-text">
                Simple, Transparent Pricing
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto mb-8">
                Choose the perfect plan for your team. Always flexible to scale.
            </p>

            <!-- Billing Toggle -->
            <div class="inline-flex items-center gap-4 bg-dark-card/50 border border-gray-700/50 rounded-lg p-2">
                <button wire:click="$set('pricingBillingPeriod', 'monthly')"
                    class="px-6 py-2 rounded-md font-semibold transition {{ $pricingBillingPeriod === 'monthly' ? 'bg-gradient-to-r from-cyan-400 to-purple-500 text-white' : 'text-gray-400 hover:text-white' }}">
                    Monthly
                </button>
                <button wire:click="$set('pricingBillingPeriod', 'yearly')"
                    class="px-6 py-2 rounded-md font-semibold transition {{ $pricingBillingPeriod === 'yearly' ? 'bg-gradient-to-r from-cyan-400 to-purple-500 text-white' : 'text-gray-400 hover:text-white' }}">
                    Yearly
                    <span class="text-xs bg-green-500/20 text-green-400 px-2 py-1 rounded ml-2">Save 20%</span>
                </button>
            </div>
        </div>

        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-3 gap-8">
            @foreach ($pricing as $key => $plan)
                <div
                    class="rounded-2xl border {{ $plan['highlighted'] ? 'border-cyan-400/50 bg-dark-card/50 ring-2 ring-cyan-400/20 transform md:scale-105' : 'border-gray-700/50 bg-dark-card/30' }} p-8 hover:border-cyan-400/75 transition">
                    <!-- Badge -->
                    @if ($plan['highlighted'])
                        <div class="text-center mb-4">
                            <span
                                class="inline-block px-4 py-1 rounded-full bg-gradient-to-r from-cyan-400 to-purple-500 text-white text-xs font-bold">
                                Most Popular
                            </span>
                        </div>
                    @endif

                    <h3 class="text-2xl font-bold mb-2">{{ $plan['name'] }}</h3>
                    <p class="text-gray-400 text-sm mb-6">{{ $plan['description'] }}</p>

                    <!-- Price -->
                    <div class="mb-8">
                        <span class="text-5xl font-bold">${{ $plan['price'] }}</span>
                        <span class="text-gray-400">/month</span>
                        @if ($pricingBillingPeriod === 'yearly' && $plan['price'] > 0)
                            <div class="text-sm text-green-400 mt-2">
                                Billed as ${{ intval($plan['price'] * 12 * 0.8) }}/year
                            </div>
                        @endif
                    </div>

                    <!-- CTA -->
                    <a href="{{ route('register') }}"
                        class="block w-full px-6 py-3 rounded-lg {{ $plan['highlighted'] ? 'bg-gradient-to-r from-cyan-400 to-purple-500 text-white' : 'border-2 border-gray-700 text-gray-300 hover:border-cyan-400/50' }} font-bold text-center transition mb-8">
                        {{ $plan['cta'] }}
                    </a>

                    <!-- Features -->
                    <ul class="space-y-3">
                        @foreach ($plan['features'] as $feature)
                            <li class="flex items-center gap-3 text-sm text-gray-300">
                                <i class="fas fa-check text-cyan-400"></i>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
