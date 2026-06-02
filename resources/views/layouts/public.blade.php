<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - AI-Powered Project Management</title>
    <meta name="description"
        content="Project Management, Reimagined by AI. Streamline your workflows with intelligent task automation.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        html {
            scroll-behavior: smooth;
            background: linear-gradient(135deg, #1e1e2f, #2c2c3e);
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #00D9FF, #9D00FF, #FF006E);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .neon-glow {
            text-shadow: 0 0 10px rgba(0, 217, 255, 0.5),
                0 0 20px rgba(157, 0, 255, 0.3);
        }

        .btn-glow {
            transition: all 0.3s ease;
        }

        .btn-glow:hover {
            transform: translateY(-2px);
        }
    </style>
    <!-- In your layout public.blade.php, add to <head> -->

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>


</head>

<body class="bg-dark-bg text-white">
    <!-- Navigation -->
    @include('components.public.navbar')

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('components.public.footer')

    <!-- Three.js for 3D scenes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    @stack('scripts')
    <!-- Initialize AOS -->
    <script>
        AOS.init({
            duration: 1000,
            offset: 120,
            once: true,
            easing: 'ease-in-out-cubic'
        });
    </script>
</body>

</html>
