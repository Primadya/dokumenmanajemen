<aside class="w-64 bg-white shadow-md flex flex-col justify-between h-screen">
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6 text-blue-600">📁 Admin Panel</h2>
        <nav class="space-y-4">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="block text-gray-700 hover:text-blue-600">📥 Notifikasi</a>
            <a href="<?php echo e(route('admin.rekap')); ?>" class="block text-gray-700 hover:text-blue-600">📋 Rekap Dokumen</a>
            <a href="<?php echo e(route('admin.users')); ?>" class="block text-gray-700 hover:text-blue-600">👤 Manajemen User</a>
            <a href="<?php echo e(route('admin.vendors')); ?>" class="block text-gray-700 hover:text-blue-600">🏢 Vendor</a>
            <a href="<?php echo e(route('admin.eps')); ?>" class="block text-gray-700 hover:text-blue-600">📎 EPS</a>
            <a href="<?php echo e(route('admin.doc.readiness')); ?>" class="block text-gray-700 hover:text-blue-600">🗂️ Kesiapan Dokumen</a>
        </nav>
    </div>
    <form action="<?php echo e(route('logout')); ?>" method="POST" class="p-6">
        <?php echo csrf_field(); ?>
        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">🚪 Logout</button>
    </form>
</aside>
<?php /**PATH C:\laragon\www\project\resources\views/partials/sidebar-admin.blade.php ENDPATH**/ ?>