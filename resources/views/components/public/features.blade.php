<section id="features" class="py-20 px-6 ">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 gradient-text tracking-tighter">
                Powerful Features for Every Team
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                Everything you need to manage projects efficiently, powered by AI.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach ($features as $feature)
                <div
                    class="group p-8 rounded-3xl border border-white/10 bg-zinc-900/70 backdrop-blur-xl hover:border-cyan-400/40 hover:bg-zinc-900 transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl hover:shadow-cyan-500/10 cursor-pointer">

                    <div
                        class="text-4xl mb-6 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                        <i class="fas {{ $feature['icon'] }} text-{{ $feature['color'] }}-400"></i>
                    </div>

                    <h3 class="text-2xl font-semibold mb-4 text-white group-hover:text-white transition-colors">
                        {{ $feature['title'] }}
                    </h3>

                    <p class="text-gray-400 leading-relaxed text-[15.2px]">
                        {{ $feature['description'] }}
                    </p>

                    <div
                        class="mt-8 w-10 h-0.5 bg-gradient-to-r from-cyan-400 to-transparent rounded-full transition-all duration-300 group-hover:w-16">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .gradient-text {
        background: linear-gradient(90deg, #67e8f9, #c084fc, #f472b6);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
</style>
