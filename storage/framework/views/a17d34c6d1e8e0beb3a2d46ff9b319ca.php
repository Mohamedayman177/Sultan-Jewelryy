<?php
    $isFree = old('is_free', $service->is_free ?? false);
?>

<?php if($errors->any()): ?>
    <div class="admin-flash admin-flash--err" style="margin:0 0 1rem;">
        <div class="admin-flash__inner">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><?php echo e($err); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>

<style>
    .svc-grid { display: grid; gap: 1rem; }
    @media (min-width: 768px) {
        .svc-grid-2 { grid-template-columns: 1fr 1fr; }
    }
    .svc-field label { display:block; font-weight:600; margin-bottom:0.35rem; font-size:0.88rem; color: var(--muted); }
    .svc-field input[type=text], .svc-field input[type=number], .svc-field textarea {
        width:100%; padding:0.55rem 0.65rem; border:1px solid var(--border); border-radius:8px; font-family:inherit; font-size:0.92rem;
    }
    .svc-field textarea { min-height: 100px; resize: vertical; }
    .svc-check { display:flex; align-items:center; gap:0.5rem; font-size:0.9rem; flex-wrap:wrap; }
    .svc-hint { font-size:0.8rem; color:var(--muted); margin-top:0.25rem; }
</style>

<div class="svc-grid">
    <div class="svc-field svc-grid-2 svc-grid" style="grid-column: 1 / -1;">
        <div>
            <label for="slug">Slug (اختياري — يُنشأ من العنوان الإنجليزي إذا تُرك فارغاً)</label>
            <input id="slug" type="text" name="slug" value="<?php echo e(old('slug', $service->slug)); ?>" dir="ltr" style="text-align:right;" placeholder="مثال: instant_consultation">
            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="svc-hint" style="color:#a44;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label for="sort_order">ترتيب العرض</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="<?php echo e(old('sort_order', $service->sort_order ?? 0)); ?>">
        </div>
    </div>

    <div class="svc-grid svc-grid-2" style="grid-column: 1 / -1;">
        <div class="svc-field">
            <label for="title_ar">العنوان (عربي)</label>
            <input id="title_ar" type="text" name="title_ar" required value="<?php echo e(old('title_ar', $service->title_ar)); ?>">
        </div>
        <div class="svc-field">
            <label for="title_en">العنوان (إنجليزي)</label>
            <input id="title_en" type="text" name="title_en" required value="<?php echo e(old('title_en', $service->title_en)); ?>" dir="ltr" style="text-align:right;">
        </div>
    </div>

    <div class="svc-field" style="grid-column: 1 / -1;">
        <label for="description_ar">الوصف (عربي)</label>
        <textarea id="description_ar" name="description_ar" required><?php echo e(old('description_ar', $service->description_ar)); ?></textarea>
    </div>
    <div class="svc-field" style="grid-column: 1 / -1;">
        <label for="description_en">الوصف (إنجليزي)</label>
        <textarea id="description_en" name="description_en" required dir="ltr"><?php echo e(old('description_en', $service->description_en)); ?></textarea>
    </div>

    <div class="svc-grid svc-grid-2" style="grid-column: 1 / -1;">
        <div class="svc-field">
            <label for="button_text_ar">نص الزر (عربي)</label>
            <input id="button_text_ar" type="text" name="button_text_ar" required value="<?php echo e(old('button_text_ar', $service->button_text_ar)); ?>">
        </div>
        <div class="svc-field">
            <label for="button_text_en">نص الزر (إنجليزي)</label>
            <input id="button_text_en" type="text" name="button_text_en" required value="<?php echo e(old('button_text_en', $service->button_text_en)); ?>" dir="ltr" style="text-align:right;">
        </div>
    </div>

    <div class="svc-field svc-grid-2 svc-grid" style="grid-column: 1 / -1;">
        <div class="svc-check">
            <input type="checkbox" name="is_free" value="1" id="is_free" <?php if(old('is_free', $service->is_free ?? false)): echo 'checked'; endif; ?>>
            <label for="is_free" style="margin:0;">خدمة مجانية</label>
        </div>
        <div class="svc-check">
            <input type="checkbox" name="requires_registration" value="1" id="requires_registration" <?php if(old('requires_registration', $service->requires_registration ?? true)): echo 'checked'; endif; ?>>
            <label for="requires_registration" style="margin:0;">يطلب نموذج التسجيل قبل واتساب</label>
        </div>
        <div class="svc-check">
            <input type="checkbox" name="is_active" value="1" id="is_active" <?php if(old('is_active', $service->is_active ?? true)): echo 'checked'; endif; ?>>
            <label for="is_active" style="margin:0;">نشط (يظهر في الموقع)</label>
        </div>
    </div>

    <div class="svc-field" style="grid-column: 1 / -1;">
        <label for="price_field">السعر (رقم — يُتجاهل عند «مجانية»)</label>
        <input id="price_field" type="number" name="price" step="0.01" min="0"
            value="<?php echo e(old('price', $service->exists && ! $service->is_free ? $service->price : '')); ?>"
            <?php if($isFree): echo 'disabled'; endif; ?>>
        <div class="svc-hint">عند إلغاء «مجانية» أدخل السعر بالريال (سيُعرض مع SAR في الموقع).</div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
  const freeCb = document.getElementById('is_free');
  const price = document.getElementById('price_field');
  if (!freeCb || !price) return;
  function sync() {
    const on = freeCb.checked;
    price.disabled = on;
    if (on) price.value = '';
  }
  freeCb.addEventListener('change', sync);
  sync();
  price.closest('form')?.addEventListener('submit', function () {
    price.disabled = false;
  });
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/admin/services/_form.blade.php ENDPATH**/ ?>