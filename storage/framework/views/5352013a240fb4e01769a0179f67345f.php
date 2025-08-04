<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-tr from-blue-50 to-white min-h-screen flex items-center justify-center">

    <!-- Login Card -->
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md border border-blue-100">

        <div class="text-center mb-6">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo Pertamina" class="mx-auto w-30 h-auto mb-3">
            <h2 class="text-3xl font-bold text-gray-800">Selamat Datang</h2>
            <p class="text-gray-500 mt-1">Silakan login untuk melanjutkan</p>
        </div>

        <!-- Error Message (via JS) -->
        <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4 text-sm text-center">
            <!-- Error akan ditampilkan di sini -->
        </div>

        <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>

            <!-- Email -->
            <div>
                <label for="email" class="block font-medium mb-1">Email</label>
                <div class="relative">
                    <input id="email" type="email" name="email" placeholder="you@example.com"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                           required>
                    <svg class="w-5 h-5 absolute top-3 right-3 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 12H8m8 0a4 4 0 00-8 0 4 4 0 008 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block font-medium mb-1">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="••••••••"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                           required>
                    <svg class="w-5 h-5 absolute top-3 right-3 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 15v2m-6-2v2m12-2v2m-6-8a4 4 0 00-4 4h8a4 4 0 00-4-4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200 shadow">
                    Masuk
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="<?php echo e(route('register')); ?>" class="text-blue-600 hover:underline font-medium">Daftar sekarang</a>
        </div>
    </div>

    <!-- Error handler -->
    <script>
        const errorMessage = document.getElementById('error-message');
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('error')) {
            errorMessage.textContent = urlParams.get('error');
            errorMessage.classList.remove('hidden');
        }
    </script>

</body>
</html>
<?php /**PATH C:\laragon\www\project\resources\views/auth/login.blade.php ENDPATH**/ ?>