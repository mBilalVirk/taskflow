{{-- <!DOCTYPE html>
<html>

<head>
    <title>Register - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h1 class="text-3xl font-bold mb-6">TaskFlow</h1>
            <h2 class="text-xl font-bold mb-6">Create Account</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Name</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                    @error('name')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                    @error('email')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Company Name</label>
                    <input type="text" name="company_name" class="w-full border rounded px-3 py-2" required>
                    @error('company_name')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                    @error('password')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2"
                        required>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded hover:bg-blue-600">
                    Create Account
                </button>

                <p class="mt-4 text-center">
                    Already have account? <a href="{{ route('login') }}" class="text-blue-500">Login</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html> --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-cyan-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-sm w-full">

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-6 text-white text-center">
                <div class="flex justify-center mb-2">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">📋</span>
                    </div>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">TaskFlow</h1>
                <p class="text-indigo-100 text-xs mt-1">Create your free account</p>
            </div>

            <!-- Form -->
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-5 text-center">Create Account</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-user text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full pl-9 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-2xl outline-none transition-all text-sm"
                                placeholder="John Doe" required>
                        </div>
                        @error('name')
                            <p class="mt-1 text-red-500 text-xs flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

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

                    <!-- Company Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-building text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" name="company_name" value="{{ old('company_name') }}"
                                class="w-full pl-9 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-2xl outline-none transition-all text-sm"
                                placeholder="Acme Corp" required>
                        </div>
                        @error('company_name')
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

                    <!-- Confirm Password -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                class="w-full pl-9 pr-10 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-2xl outline-none transition-all text-sm"
                                placeholder="••••••••" required>
                            <button type="button" onclick="toggleConfirmPassword()"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i id="eye-icon-confirm" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold py-3 rounded-2xl transition-all flex items-center justify-center gap-2 text-sm">
                        Create Account
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <!-- Login Link -->
                <p class="text-center mt-5 text-gray-600 text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Sign
                        in</a>
                </p>
            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const field = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function toggleConfirmPassword() {
            const field = document.getElementById('password_confirmation');
            const icon = document.getElementById('eye-icon-confirm');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
