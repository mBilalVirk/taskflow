<!DOCTYPE html>
<html>

<head>
    <title>Login - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h1 class="text-3xl font-bold mb-6">TaskFlow</h1>
            <h2 class="text-xl font-bold mb-6">Login</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                    @error('email')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                    @error('password')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded hover:bg-blue-600">
                    Login
                </button>

                <p class="mt-4 text-center">
                    Don't have account? <a href="{{ route('register') }}" class="text-blue-500">Register</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>
