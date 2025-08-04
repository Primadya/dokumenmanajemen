<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <main class="container mx-auto py-10 px-4">
        <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">

            <h2 class="text-2xl font-semibold text-center mb-6">Registrasi</h2>

            <!-- Pesan Error -->
            <div id="error-message" class="hidden bg-red-100 text-red-700 p-2 rounded mb-4">
                <ul id="error-list"></ul>
            </div>

            <form method="POST" action="/register">
                <!-- Token CSRF Laravel -->
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                <div class="mb-3">
                    <label class="block font-medium">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Email</label>
                    <input type="email" name="email" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Nomor Telepon</label>
                    <input type="text" name="phone" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Departemen</label>
                    <input type="text" name="division" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Role</label>
                    <select name="role" class="w-full border px-3 py-2 rounded" required>
                        <option value="admin">Admin</option>
                        <option value="PERTAMINA">PERTAMINA</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Password</label>
                    <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full border px-3 py-2 rounded" required>
                </div>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full hover:bg-green-700 transition duration-200">
                    Daftar
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="/login" class="text-sm text-blue-600 hover:underline">Sudah punya akun? Login</a>
            </div>
        </div>
    </main>

    <!-- Script error dinamis jika ingin menampilkan pesan -->
    <script>
        const params = new URLSearchParams(window.location.search);
        if (params.has('errors')) {
            const errorMessage = document.getElementById('error-message');
            const errorList = document.getElementById('error-list');
            const errors = JSON.parse(decodeURIComponent(params.get('errors')));

            errors.forEach(msg => {
                const li = document.createElement('li');
                li.textContent = msg;
                errorList.appendChild(li);
            });

            errorMessage.classList.remove('hidden');
        }
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\project\resources\views/auth/register.blade.php ENDPATH**/ ?>