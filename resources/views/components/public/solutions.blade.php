<section id="solutions" class="py-20 px-6" data-aos="slide-up">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 gradient-text">
                Built for Every Industry
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
                TaskFlow adapts to your team's unique workflow and needs.
            </p>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            @foreach ($solutions as $key => $solution)
                <button wire:click="switchTab('{{ $key }}')"
                    class="px-6 py-3 rounded-lg font-semibold transition {{ $activeTab === $key ? 'bg-gradient-to-r from-cyan-400 to-purple-500 text-white' : 'border border-gray-700 text-gray-300 hover:text-white' }}">
                    <i class="fas {{ $solution['icon'] }} mr-2"></i>{{ $solution['title'] }}
                </button>
            @endforeach
        </div>

        <!-- Active Solution Content -->
        @if (isset($solutions[$activeTab]))
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-3xl font-bold mb-4">{{ $solutions[$activeTab]['title'] }}</h3>
                    <p class="text-gray-400 text-lg mb-8">{{ $solutions[$activeTab]['description'] }}</p>

                    <div class="space-y-4">
                        @foreach ($solutions[$activeTab]['metrics'] as $metric)
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-gradient-to-r from-cyan-400 to-purple-500">
                                </div>
                                <span class="text-gray-300">{{ $metric }}</span>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('register') }}"
                        class="inline-block mt-8 px-8 py-3 rounded-lg bg-gradient-to-r from-cyan-400 to-purple-500 text-white font-semibold hover:shadow-lg transition">
                        Try for Free
                    </a>
                </div>

                <div class="hidden md:block">
                    <div
                        class="rounded-xl bg-dark-card/50 border border-gray-700/50 p-8 h-80 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas {{ $solutions[$activeTab]['icon'] }} text-6xl text-gray-600 mb-4"></i>
                            <p class="text-gray-500">Dashboard Preview</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
