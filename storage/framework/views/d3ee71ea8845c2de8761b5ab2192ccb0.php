<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>📎 EPS - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex bg-gray-100 h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col justify-between">
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">📁 Admin Panel</h2>
            <nav class="space-y-4">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="block text-gray-700 hover:text-blue-600">📥 Notifikasi</a>
                <a href="<?php echo e(route('admin.rekap')); ?>" class="block text-gray-700 hover:text-blue-600">📋 Rekap Dokumen</a>
                <a href="<?php echo e(route('admin.users')); ?>" class="block text-gray-700 hover:text-blue-600">👤 Manajemen User</a>
                <a href="<?php echo e(route('admin.vendors')); ?>" class="block text-gray-700 hover:text-blue-600">🏢 Vendor</a>
                <a href="<?php echo e(route('admin.eps')); ?>" class="block text-blue-600 font-semibold">📎 EPS</a>
                <a href="<?php echo e(route('admin.doc.readiness')); ?>" class="block text-gray-700 hover:text-blue-600">🗂️ Kesiapan Dokumen</a>
            </nav>
        </div>
        <form action="<?php echo e(route('logout')); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">🚪 Logout</button>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">📎 Daftar EPS</h1>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-200 text-gray-800 text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">No EPS</th>
                        <th class="px-4 py-3 text-left">Nama Vendor</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $eps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-4 py-3 text-gray-800"><?php echo e($item->eps_number); ?></td>
                            <td class="px-4 py-3 text-gray-700"><?php echo e($item->vendor->name ?? '-'); ?></td>
                            <td class="px-4 py-3 text-gray-700"><?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')); ?></td>
                            <td class="px-4 py-3 text-gray-700">
                                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm">
                                    <?php echo e($item->status ?? 'Belum Ditentukan'); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-6">
                                Tidak ada data EPS tersedia.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
<?php /**PATH C:\laragon\www\project\resources\views/admin/eps.blade.php ENDPATH**/ ?>