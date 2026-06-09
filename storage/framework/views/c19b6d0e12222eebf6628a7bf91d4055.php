<?php $__env->startSection('title', 'رابط دفع جديد'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-card">
    <h1 class="admin-h1">رابط دفع جديد</h1>
    <p class="muted" style="margin-top:0;margin-bottom:1.25rem;">
        أدخل بيانات العميل والمبلغ؛ سيُنشأ رابط MyFatoorah يمكنك نسخه وإرساله للعميل.
    </p>

    <form method="post" action="<?php echo e(route('admin.payment-links.store')); ?>">
        <?php echo csrf_field(); ?>

        <?php if($errors->any()): ?>
            <div class="admin-flash admin-flash--err" style="margin:0 0 1rem;">
                <div class="admin-flash__inner">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p style="margin:0 0 0.35rem;"><?php echo e($err); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <style>
            .plink-grid { display: grid; gap: 1rem; }
            @media (min-width: 768px) { .plink-grid-2 { grid-template-columns: 1fr 1fr; } }
            .plink-field label { display:block; font-weight:600; margin-bottom:0.35rem; font-size:0.88rem; color: var(--muted); }
            .plink-field input, .plink-field textarea {
                width:100%; padding:0.55rem 0.65rem; border:1px solid var(--border); border-radius:8px; font-family:inherit; font-size:0.92rem;
            }
            .plink-field textarea { min-height: 80px; resize: vertical; }
            .plink-hint { font-size:0.8rem; color:var(--muted); margin-top:0.25rem; }
        </style>

        <div class="plink-grid">
            <div class="plink-grid plink-grid-2">
                <div class="plink-field">
                    <label for="customer_name">اسم العميل (اختياري)</label>
                    <input id="customer_name" type="text" name="customer_name" value="<?php echo e(old('customer_name')); ?>">
                </div>
                <div class="plink-field">
                    <label for="phone">رقم الجوال</label>
                    <input id="phone" type="text" name="phone" required value="<?php echo e(old('phone')); ?>" dir="ltr" style="text-align:right;" placeholder="05xxxxxxxx">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="plink-hint" style="color:#a44;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="plink-grid plink-grid-2">
                <div class="plink-field">
                    <label for="email">البريد الإلكتروني (اختياري)</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" dir="ltr" style="text-align:right;">
                </div>
                <div class="plink-field">
                    <label for="amount">المبلغ (ر.س)</label>
                    <input id="amount" type="number" name="amount" step="0.01" min="0.01" required value="<?php echo e(old('amount')); ?>" dir="ltr" style="text-align:right;">
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="plink-hint" style="color:#a44;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="plink-field">
                <label for="description">وصف / ملاحظة (اختياري)</label>
                <textarea id="description" name="description" placeholder="مثال: دفعة مقدمة"><?php echo e(old('description')); ?></textarea>
            </div>
        </div>

        <div class="admin-actions" style="margin-top:1rem;">
            <button type="submit" class="btn-admin btn-admin--primary">إنشاء رابط الدفع</button>
            <a href="<?php echo e(route('admin.payment-links.index')); ?>" class="btn-admin btn-admin--muted">إلغاء</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/admin/payment-links/create.blade.php ENDPATH**/ ?>