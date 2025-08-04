<!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-10 rounded-lg shadow-lg text-center max-w-md w-full">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Selamat Datang di Sistem Transmital</h1>
        <div class="space-y-4">
            <a href="{{ route('login') }}" class="block bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200">Login</a>
            <a href="{{ route('register') }}" class="block bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-200">Registrasi</a>
        </div>
    </div>
</body>
</html> -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset('images/bg.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen">
    <div class="bg-white bg-opacity-80 p-10 rounded-lg shadow-lg text-center max-w-md w-full">
        <!-- <h1 class="text-3xl font-bold text-gray-800 mb-6">Selamat Datang di Sistem Transmital</h1> -->

        <img src="{{ asset('images\logo.png') }}" alt="Logo Pertamina" class="mx-auto mb-4 w-50 h-auto">

        <div class="space-y-4">
            <a href="{{ route('login') }}" class="block bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200">Login</a>
            <a href="{{ route('register') }}" class="block bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-200">Registrasi</a>
        </div>
    </div>
</body>
</html>
