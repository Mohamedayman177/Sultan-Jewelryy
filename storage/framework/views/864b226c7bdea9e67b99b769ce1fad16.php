<?php $__env->startSection('title', 'تعديل خدمة'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-card">
    <h1 class="admin-h1">تعديل الخدمة</h1>
    <form method="post" action="<?php echo e(route('admin.services.update', $service)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <?php echo $__env->make('admin.services._form', ['service' => $service], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="admin-actions" style="margin-top:1rem;">
            <button type="submit" class="btn-admin btn-admin--primary">تحديث</button>
            <a href="<?php echo e(route('admin.services.index')); ?>" class="btn-admin btn-admin--muted">إلغاء</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/admin/services/edit.blade.php ENDPATH**/ ?>