<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pertamina Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex min-h-screen">
    
    <aside class="w-64 bg-green-900 text-white p-5 space-y-4">
        <h1 class="text-2xl font-bold">PERTAMINA</h1>
        <nav class="space-y-2">
            <a href="<?php echo e(route('pertamina.dashboard')); ?>" class="block hover:underline">Dashboard</a>
            <a href="<?php echo e(route('pertamina.upload.form')); ?>" class="block hover:underline">Upload Dokumen</a>
            <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-4">
                <?php echo csrf_field(); ?>
                <button class="text-red-400 hover:underline">Logout</button>
            </form>
        </nav>
    </aside>

    
    <main class="flex-1 p-6 bg-white">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold mb-6 text-center">Upload Dokumen ke Admin</h2>

            <?php if($errors->any()): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($e); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            
            <form method="POST" action="<?php echo e(route('pertamina.upload.store')); ?>" enctype="multipart/form-data" class="bg-gray-50 p-6 rounded-lg shadow-md">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Nomor Dokumen</label>
                        <input type="text" name="document_number" class="border rounded w-full p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Judul Dokumen</label>
                        <input type="text" name="title" class="border rounded w-full p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Revisi</label>
                        <input type="text" name="revision" class="border rounded w-full p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Status</label>
                        <select name="status" class="border rounded w-full p-2" required>
                            <option value="approve">Approve</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Catatan</label>
                    <textarea name="notes" class="border rounded w-full p-2" rows="3"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Upload File (PDF)</label>
                    <input type="file" name="file" class="border rounded w-full p-2" accept="application/pdf" required>
                </div>

                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Kirim ke Admin</button>
            </form>

            
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-4">Rekap Transmital</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2 text-left">Nomor Transmital</th>
                                <th class="border px-4 py-2 text-left">Nomor Dokumen</th>
                                <th class="border px-4 py-2 text-left">Judul</th>
                                <th class="border px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $transmittals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="border px-4 py-2"><?php echo e($item->transmittal_number); ?></td>
                                    <td class="border px-4 py-2"><?php echo e($item->document_number); ?></td>
                                    <td class="border px-4 py-2"><?php echo e($item->title); ?></td>
                                    <td class="border px-4 py-2 text-center">
                                        <?php if($item->document_path): ?>
                                            <a href="<?php echo e(asset('storage/' . $item->document_path)); ?>" target="_blank" class="text-blue-600 hover:underline">Lihat PDF</a>
                                        <?php else: ?>
                                            <span class="text-gray-500 italic">Tidak ada file</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="border px-4 py-4 text-center text-gray-500">Belum ada dokumen.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>

</body>
</html>
<?php /**PATH C:\laragon\www\project\resources\views/pertamina/upload.blade.php ENDPATH**/ ?>