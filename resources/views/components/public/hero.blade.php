<section id="hero" class="min-h-screen relative overflow-hidden pt-32 pb-20 px-6 flex items-center" wire:ignore.self>

    <!-- Dynamic Background Gradient -->
    <div class="absolute inset-0 -z-10" id="hero-bg"></div>

    <!-- Mouse-following Glow Orbs -->
    <div id="mouse-glow-1"
        class="absolute w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none transition-all duration-300 -z-10">
    </div>
    <div id="mouse-glow-2"
        class="absolute w-96 h-96 bg-purple-500/10 rounded-full blur-3xl pointer-events-none transition-all duration-300 -z-10">
    </div>

    <div class="max-w-4xl mx-auto text-center relative z-10">

        <!-- Typewriter Heading -->
        <h1 id="typewriter" wire:ignore
            class="text-5xl md:text-7xl font-bold mb-6 leading-none tracking-tighter min-h-[160px] cursor-default transition-all duration-300">
        </h1>

        <p data-aos="zoom-in"
            class="text-lg md:text-xl text-gray-400 mb-12 max-w-2xl mx-auto leading-relaxed transition-colors duration-300 hover:text-gray-300">
            Streamline your workflows, automate task delegation, and watch your productivity flow.
            <span class="text-cyan-400">TaskFlow</span> uses advanced AI to understand your projects and help you work
            smarter.
        </p>

        <!-- CTAs -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-20" data-aos="zoom-in">
            <a href="{{ route('register') }}"
                class="group px-8 py-4 rounded-2xl bg-gradient-to-r from-cyan-400 to-purple-500 text-white font-bold text-lg hover:shadow-2xl hover:shadow-cyan-500/50 transition-all duration-300 hover:scale-105 w-full sm:w-auto text-center">
                Get Started Free
            </a>

            <button onclick="watchDemo()"
                class="group px-8 py-4 rounded-2xl border-2 border-cyan-400 text-cyan-400 font-bold text-lg hover:bg-cyan-400 hover:text-black transition-all duration-300 hover:scale-105 w-full sm:w-auto">
                <i class="fas fa-play mr-2"></i>Watch Demo
            </button>
        </div>

        <!-- Social Proof -->
        <div class="text-center text-gray-500 text-sm">
            <p class="mb-4">Trusted by 5,000+ teams worldwide</p>
            <div class="flex justify-center gap-6 flex-wrap">
                <span class="flex items-center gap-2 hover:text-yellow-400 transition-colors">
                    <i class="fas fa-star"></i> 4.9/5 on Capterra
                </span>
                <span class="flex items-center gap-2 hover:text-cyan-400 transition-colors">
                    <i class="fas fa-award"></i> Fastest-Growing PM Tool
                </span>
            </div>
        </div>
    </div>

    <!-- 3D Kanban Canvas -->
    <div class="absolute right-10 bottom-10 hidden lg:block w-[420px] h-[420px]" wire:ignore>
        <canvas id="hero-3d-canvas" class="rounded-3xl shadow-2xl"></canvas>
    </div>
</section>

<script>
    document.addEventListener('livewire:navigated', () => {
        initTypewriter();
        initMouseGlow();
        initHero3D();
    });

    // ==================== TYPEWRITER EFFECT ====================
    function initTypewriter() {
        const text = "Project Management, Reimagined by AI";
        const element = document.getElementById('typewriter');
        let index = 0;
        element.innerHTML = '';

        function type() {
            if (index < text.length) {
                element.innerHTML += text.charAt(index) === ',' ? ',<br>' : text.charAt(index);
                index++;
                setTimeout(type, 60);
            }
        }
        type();
    }

    // ==================== MOUSE INTERACTIVE GLOW ====================
    function initMouseGlow() {
        const glow1 = document.getElementById('mouse-glow-1');
        const glow2 = document.getElementById('mouse-glow-2');
        const hero = document.getElementById('hero');

        hero.addEventListener('mousemove', (e) => {
            const x = e.clientX;
            const y = e.clientY;

            glow1.style.left = `${x - 200}px`;
            glow1.style.top = `${y - 250}px`;

            glow2.style.left = `${x - 100}px`;
            glow2.style.top = `${y - 80}px`;
        });
    }

    // ==================== 3D KANBAN BOARD ====================
    let scene, camera, renderer;

    function initHero3D() {
        const canvas = document.getElementById('hero-3d-canvas');
        if (!canvas) return;

        renderer = new THREE.WebGLRenderer({
            canvas,
            antialias: true,
            alpha: true
        });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.setSize(420, 420);

        scene = new THREE.Scene();
        camera = new THREE.PerspectiveCamera(45, 1, 0.1, 100);
        camera.position.z = 9;

        const ambient = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambient);
        const pointLight = new THREE.PointLight(0x22d3ee, 2.5, 100);
        pointLight.position.set(5, 5, 8);
        scene.add(pointLight);

        const board = new THREE.Mesh(
            new THREE.BoxGeometry(5.5, 3.8, 0.4),
            new THREE.MeshPhongMaterial({
                color: 0x1f2937,
                shininess: 90
            })
        );
        scene.add(board);

        const cardColors = [0x22d3ee, 0xa855f7, 0xf472b6];
        for (let i = 0; i < 5; i++) {
            const card = new THREE.Mesh(
                new THREE.PlaneGeometry(1.3, 1.7),
                new THREE.MeshPhongMaterial({
                    color: cardColors[i % 3],
                    shininess: 100,
                    side: THREE.DoubleSide
                })
            );
            card.position.set(
                (i % 3) * 1.7 - 1.5,
                Math.floor(i / 3) * -2 + 0.6,
                0.6 + Math.random() * 0.5
            );
            card.rotation.x = -0.15 + Math.random() * 0.1;
            scene.add(card);
        }

        let targetRotationY = 0;
        canvas.addEventListener('mousemove', (e) => {
            const rect = canvas.getBoundingClientRect();
            const mouseX = (e.clientX - rect.left) / rect.width;
            targetRotationY = (mouseX - 0.5) * 0.8;
        });

        function animate() {
            requestAnimationFrame(animate);
            scene.rotation.y = scene.rotation.y * 0.92 + targetRotationY * 0.08;
            renderer.render(scene, camera);
        }
        animate();
    }

    function watchDemo() {
        alert("🎥 Demo video would play here");
    }
</script>

<style>
    #typewriter {
        background: linear-gradient(to right, #67e8f9, #c084fc);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        transition: all 0.4s ease;
    }

    #typewriter:hover {
        background: linear-gradient(to right, #22d3ee, #a855f7);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
</style>
