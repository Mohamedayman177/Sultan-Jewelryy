<option value="">—</option>
<?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $labels): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <option value="<?php echo e($key); ?>" data-label-ar="<?php echo e($labels['ar']); ?>" data-label-en="<?php echo e($labels['en']); ?>"><?php echo e($labels['ar']); ?></option>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/partials/customer-form-yes-no.blade.php ENDPATH**/ ?>