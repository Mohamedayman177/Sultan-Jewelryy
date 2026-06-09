<?php $__env->startSection('title', 'العملاء المسجّلون'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-card">
    <h1 class="admin-h1">العملاء المسجّلون</h1>
    <p class="muted" style="margin-top:0;margin-bottom:1.25rem;">
        البيانات المرسلة من نموذج الخدمات التي تتطلّب التسجيل قبل التوجيه إلى واتساب.
    </p>

    <?php if($customers->isEmpty()): ?>
        <p class="muted">لا يوجد عملاء مسجّلون بعد.</p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الهوية</th>
                        <th>الجوال</th>
                        <th>البريد</th>
                        <th>الخدمة</th>
                        <th>الدفع</th>
                        <th>اللغة</th>
                        <th>تاريخ التسجيل</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($customer->id); ?></td>
                            <td><?php echo e($customer->name ?: '—'); ?></td>
                            <td dir="ltr" style="text-align:right;"><?php echo e($customer->national_id ?: '—'); ?></td>
                            <td dir="ltr" style="text-align:right;"><?php echo e($customer->phone); ?></td>
                            <td dir="ltr" style="text-align:right;"><?php echo e($customer->email ?: '—'); ?></td>
                            <td>
                                <?php if($customer->service): ?>
                                    <?php echo e($customer->service->title_ar); ?>

                                    <span class="muted">/ <?php echo e($customer->service->title_en); ?></span>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="muted">
                                <?php if($customer->payment_status === 'paid'): ?>
                                    مدفوع
                                <?php elseif($customer->payment_status === 'pending'): ?>
                                    بانتظار الدفع
                                <?php elseif($customer->payment_status === 'failed'): ?>
                                    فشل الدفع
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(strtoupper($customer->locale ?? '—')); ?></td>
                            <td class="muted"><?php echo e($customer->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <?php echo e($customers->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/admin/customers/index.blade.php ENDPATH**/ ?>