<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-cyan-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-sm w-full">

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            <!-- Compact Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-6 text-white text-center">
                <div class="flex justify-center mb-2">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">📋</span>
                    </div>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">TaskFlow</h1>
                <p class="text-indigo-100 text-xs mt-1">Get things done together</p>
            </div>

            <!-- Form -->
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-5 text-center">Welcome back</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-gray-400 text-sm"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full pl-9 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-2xl outline-none transition-all text-sm"
                                placeholder="you@example.com" required>
                        </div>
                        @error('email')
                            <p class="mt-1 text-red-500 text-xs flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                            </div>
                            <input id="password" type="password" name="password"
                                class="w-full pl-9 pr-10 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-2xl outline-none transition-all text-sm"
                                placeholder="••••••••" required>
                            <!-- FIXED: Added type="button" -->
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i id="eye-icon" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-red-500 text-xs flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div class="flex items-center justify-between mb-5 text-sm">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 accent-indigo-600 rounded">
                            <span class="text-gray-600 text-sm">Remember me</span>
                        </label>
                        <a href="#" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">Forgot
                            password?</a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold py-3 rounded-2xl transition-all flex items-center justify-center gap-2 text-sm">
                        Sign In
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <!-- Register Link -->
                <p class="text-center mt-5 text-gray-600 text-sm">
                    No account?
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Sign
                        up</a>
                </p>
            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
