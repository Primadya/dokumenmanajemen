<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            background-color: #f7fafc;
        }

        .sidebar {
            width: 250px;
            background-color: #1e3a8a;
            color: white;
            padding: 1.5rem;
            min-height: 100vh;
        }

        .sidebar h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .sidebar nav a {
            display: block;
            color: white;
            margin-bottom: 0.75rem;
            text-decoration: none;
        }

        .sidebar nav a:hover {
            text-decoration: underline;
        }

        .content {
            flex: 1;
            padding: 2rem;
            background-color: white;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        button.logout {
            margin-top: 2rem;
            color: #f87171;
            background: none;
            border: none;
            cursor: pointer;
        }

        button.logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="layout">
    {{-- Sidebar --}}
    <aside class="sidebar">
        <h1>Admin Panel</h1>
        <nav>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.rekap') }}">Rekap</a>
            <a href="{{ route('admin.users') }}">User</a>
            <a href="{{ route('admin.vendors') }}">Vendor</a>
            <a href="{{ route('admin.eps') }}">EPS</a>
            <a href="{{ route('admin.doc.readiness') }}">Dokumen Ready</a>
        </nav>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout">Logout</button>
        </form>
    </aside>

    {{-- Konten --}}
    <main class="content">
        @yield('content')
    </main>
</div>

</body>
</html>
