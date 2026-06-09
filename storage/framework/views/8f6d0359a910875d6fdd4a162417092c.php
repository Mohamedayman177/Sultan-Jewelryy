<?php $__env->startSection('title', 'الخدمات'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
        <h1 class="admin-h1" style="margin:0;">الخدمات المعروضة في الموقع</h1>
        <a href="<?php echo e(route('admin.services.create')); ?>" class="btn-admin btn-admin--primary">إضافة خدمة</a>
    </div>
    <p class="muted" style="margin-top:0;">
        ترتيب العرض حسب «ترتيب العرض». الخدمات المعطّلة لا تظهر في الصفحة الرئيسية.
    </p>

    <?php if($services->isEmpty()): ?>
        <p class="muted">لا توجد خدمات بعد.</p>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ترتيب</th>
                        <th>العنوان (عربي)</th>
                        <th>Slug</th>
                        <th>السعر</th>
                        <th>تسجيل قبل واتساب</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($service->sort_order); ?></td>
                            <td><?php echo e($service->title_ar); ?></td>
                            <td class="muted" dir="ltr" style="text-align:right;"><?php echo e($service->slug); ?></td>
                            <td>
                                <?php if($service->is_free): ?>
                                    <span class="muted">مجاني</span>
                                <?php else: ?>
                                    <?php echo e(number_format((float) $service->price, 0)); ?> SAR
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($service->requires_registration ? 'نعم' : 'لا (واتساب مباشر)'); ?></td>
                            <td><?php echo e($service->is_active ? 'نشط' : 'معطّل'); ?></td>
                            <td>
                                <div class="admin-actions">
                                    <a href="<?php echo e(route('admin.services.edit', $service)); ?>" class="btn-admin btn-admin--muted">تعديل</a>
                                    <form action="<?php echo e(route('admin.services.destroy', $service)); ?>" method="post" onsubmit="return confirm('حذف هذه الخدمة؟');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-admin btn-admin--danger">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php echo e($services->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/admin/services/index.blade.php ENDPATH**/ ?>