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

        body.light {
            background: #61c2ff;
            color: #111827;
        }

        body.light .text-gray-300 {
            color: #4b5563 !important;
        }

        body.light .text-gray-400 {
            color: #6b7280 !important;
        }

        body.light .text-gray-500 {
            color: #6b7280 !important;
        }

        body.light .bg-dark-card {
            background-color: #f9fafb !important;
        }

        body.light .bg-dark-bg {
            background-color: #f8fafc !important;
        }

        body.light .border-gray-800 {
            border-color: #e5e7eb !important;
        }

        body.light .border-gray-700 {
            border-color: #d1d5db !important;
        }

        body.light .glassmorphism {
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(209, 213, 219, 0.75);
        }
    </style>
    <!-- In your layout public.blade.php, add to <head> -->

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>


</head>

<body id="dark-mode" class="dark bg-dark-bg text-white">
    <!-- Navigation -->
    @include('components.public.navbar')
    @include('components.public.animate-progress')

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
