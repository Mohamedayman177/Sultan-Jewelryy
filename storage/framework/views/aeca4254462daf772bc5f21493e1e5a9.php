<?php
    $j = config('customer-form.jewelry');
    $yn = $j['yes_no_unknown'];
?>

<div class="customer-modal__section" data-category-panel="jewelry">
    <h4 class="customer-modal__section-title">
        <span data-lang="ar" data-lang-display="block">تفاصيل المجوهرات</span>
        <span data-lang="en" data-lang-display="block">Jewelry details</span>
    </h4>

    <div class="customer-modal__field">
        <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">نوع القطعة</span><span data-lang="en" data-lang-display="inline">Piece type</span></label>
        <select class="customer-modal__input" name="piece_type">
            <option value="">—</option>
            <?php $__currentLoopData = $j['piece_types']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $labels): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($key); ?>" data-label-ar="<?php echo e($labels['ar']); ?>" data-label-en="<?php echo e($labels['en']); ?>"><?php echo e($labels['ar']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="customer-modal__field">
        <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">نوع المعدن</span><span data-lang="en" data-lang-display="inline">Metal type</span></label>
        <select class="customer-modal__input" name="metal_type">
            <option value="">—</option>
            <?php $__currentLoopData = $j['metal_types']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $labels): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($key); ?>" data-label-ar="<?php echo e($labels['ar']); ?>" data-label-en="<?php echo e($labels['en']); ?>"><?php echo e($labels['ar']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">عدد القطع</span><span data-lang="en" data-lang-display="inline">Pieces count</span></label>
            <input class="customer-modal__input" type="number" name="pieces_count" min="1" max="999">
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">الوزن التقريبي</span><span data-lang="en" data-lang-display="inline">Approx. weight</span></label>
            <input class="customer-modal__input" type="text" name="approximate_weight" maxlength="64" placeholder="مثال: 15 جرام">
        </div>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">العيار</span><span data-lang="en" data-lang-display="inline">Karat</span></label>
            <input class="customer-modal__input" type="text" name="karat" maxlength="32" placeholder="18">
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">الماركة</span><span data-lang="en" data-lang-display="inline">Brand</span></label>
            <input class="customer-modal__input" type="text" name="brand" maxlength="128">
        </div>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">دمغة أو ختم؟</span><span data-lang="en" data-lang-display="inline">Hallmark?</span></label>
            <select class="customer-modal__input" name="has_hallmark"><?php echo $__env->make('partials.customer-form-yes-no', ['options' => $yn], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></select>
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">فاتورة؟</span><span data-lang="en" data-lang-display="inline">Invoice?</span></label>
            <select class="customer-modal__input" name="has_invoice"><?php echo $__env->make('partials.customer-form-yes-no', ['options' => $yn], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></select>
        </div>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">شهادة؟</span><span data-lang="en" data-lang-display="inline">Certificate?</span></label>
            <select class="customer-modal__input" name="has_certificate"><?php echo $__env->make('partials.customer-form-yes-no', ['options' => $yn], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></select>
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">حالة القطعة</span><span data-lang="en" data-lang-display="inline">Condition</span></label>
            <select class="customer-modal__input" name="piece_condition">
                <option value="">—</option>
                <?php $__currentLoopData = $j['piece_conditions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $labels): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" data-label-ar="<?php echo e($labels['ar']); ?>" data-label-en="<?php echo e($labels['en']); ?>"><?php echo e($labels['ar']); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/partials/customer-form-jewelry.blade.php ENDPATH**/ ?>