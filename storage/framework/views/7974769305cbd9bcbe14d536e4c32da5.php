<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Vendor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function openEditModal(id, email, telephone, pic_name) {
            document.getElementById('editForm').action = `/admin/vendors/${id}`;
            document.getElementById('editEmail').value = email;
            document.getElementById('editTelephone').value = telephone;
            document.getElementById('editPic').value = pic_name;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.vendor-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
        }
    </script>
</head>
<body class="flex bg-gray-100 min-h-screen">

    <?php echo $__env->make('partials.sidebar-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">🏢 Vendor</h1>

        
        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 mb-4 rounded">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 mb-4 rounded">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        
        <form action="<?php echo e(route('admin.vendors.import')); ?>" method="POST" enctype="multipart/form-data" class="mb-6">
            <?php echo csrf_field(); ?>
            <div class="flex items-center space-x-4">
                <input type="file" name="file" accept=".xlsx,.xls" required class="border border-gray-300 rounded px-3 py-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    ⬆️ Upload Excel
                </button>
            </div>
        </form>

        
        <form action="<?php echo e(route('admin.vendors.search')); ?>" method="GET" class="flex flex-wrap gap-2 mb-6">
            <input type="text" name="query" value="<?php echo e(request('query')); ?>" placeholder="Cari Vendor atau PIC" class="border border-gray-300 rounded px-3 py-2 flex-1 min-w-[200px]">
            <select name="sort" class="border border-gray-300 rounded px-3 py-2">
                <option value="">Urutkan</option>
                <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>Nama Perusahaan (A-Z)</option>
                <option value="pic_name" <?php echo e(request('sort') == 'pic_name' ? 'selected' : ''); ?>>Nama PIC (A-Z)</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                🔍 Cari
            </button>
        </form>

        
        <form action="<?php echo e(route('admin.vendors.bulkDelete')); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus vendor yang dipilih?')">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="overflow-x-auto">
                <table class="w-full bg-white border rounded shadow text-sm">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2 text-center"><input type="checkbox" onclick="toggleSelectAll(this)"></th>
                            <th class="border px-4 py-2">Nama Vendor</th>
                            <th class="border px-4 py-2">Alamat</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Telepon</th>
                            <th class="border px-4 py-2">PIC</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2 text-center">
                                    <input type="checkbox" name="selected_vendors[]" value="<?php echo e($vendor->id); ?>" class="vendor-checkbox">
                                </td>
                                <td class="border px-4 py-2"><?php echo e($vendor->name); ?></td>
                                <td class="border px-4 py-2"><?php echo e($vendor->address); ?></td>
                                <td class="border px-4 py-2"><?php echo e($vendor->email); ?></td>
                                <td class="border px-4 py-2"><?php echo e($vendor->telephone); ?></td>
                                <td class="border px-4 py-2"><?php echo e($vendor->pic_name); ?></td>
                                <td class="border px-4 py-2 text-center">
                                    <button type="button" onclick="openEditModal(<?php echo e($vendor->id); ?>, '<?php echo e($vendor->email); ?>', '<?php echo e($vendor->telephone); ?>', '<?php echo e($vendor->pic_name); ?>')" class="text-blue-600 hover:underline">✏️ Edit</button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-8">Belum ada data vendor.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">🗑️ Hapus yang Dipilih</button>
            </div>
        </form>
    </main>

    
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Edit Vendor</h2>
            <form id="editForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="editEmail" name="email" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" id="editTelephone" name="telephone" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama PIC</label>
                    <input type="text" id="editPic" name="pic_name" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\project\resources\views/admin/vendor.blade.php ENDPATH**/ ?>