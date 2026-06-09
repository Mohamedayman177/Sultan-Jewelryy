<?php $__env->startSection('title', 'روابط الدفع'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-card">
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:0.75rem;margin-bottom:1.25rem;">
        <div>
            <h1 class="admin-h1" style="margin-bottom:0.35rem;">روابط الدفع</h1>
            <p class="muted" style="margin:0;">إنشاء رابط دفع بمبلغ محدد وإرساله للعميل.</p>
        </div>
        <a href="<?php echo e(route('admin.payment-links.create')); ?>" class="btn-admin btn-admin--primary">رابط دفع جديد</a>
    </div>

    <?php if(session('created_payment_url')): ?>
        <div style="margin-bottom:1.25rem;padding:0.85rem 1rem;border-radius:10px;background:rgba(46,125,50,0.08);border:1px solid rgba(46,125,50,0.35);">
            <p style="margin:0 0 0.5rem;font-weight:600;color:#1b5e20;">رابط الدفع الجديد</p>
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;align-items:center;">
                <input type="text" readonly value="<?php echo e(session('created_payment_url')); ?>" id="created-payment-url" dir="ltr" style="flex:1;min-width:200px;padding:0.5rem 0.65rem;border:1px solid var(--border);border-radius:8px;font-size:0.85rem;background:#fff;">
                <button type="button" class="btn-admin btn-admin--primary" id="copy-created-url">نسخ الرابط</button>
            </div>
        </div>
    <?php endif; ?>

    <?php if($paymentLinks->isEmpty()): ?>
        <p class="muted">لا توجد روابط دفع بعد. اضغط «رابط دفع جديد» للبدء.</p>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>الجوال</th>
                        <th>المبلغ</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>رابط الدفع</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $paymentLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($link->id); ?></td>
                            <td><?php echo e($link->customer_name ?: '—'); ?></td>
                            <td dir="ltr" style="text-align:right;"><?php echo e($link->phone); ?></td>
                            <td><?php echo e(number_format((float) $link->amount, 2)); ?> ر.س</td>
                            <td class="muted"><?php echo e($link->description ?: '—'); ?></td>
                            <td>
                                <?php if($link->payment_status === 'paid'): ?>
                                    <span style="color:#1b5e20;font-weight:600;">مدفوع</span>
                                <?php elseif($link->payment_status === 'pending'): ?>
                                    بانتظار الدفع
                                <?php elseif($link->payment_status === 'failed'): ?>
                                    فشل الدفع
                                <?php else: ?>
                                    <?php echo e($link->payment_status); ?>

                                <?php endif; ?>
                            </td>
                            <td class="muted"><?php echo e($link->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i')); ?></td>
                            <td>
                                <?php if($link->invoice_url && $link->payment_status === 'pending'): ?>
                                    <button type="button" class="btn-admin btn-admin--muted copy-link-btn" data-url="<?php echo e($link->invoice_url); ?>">نسخ</button>
                                <?php else: ?>
                                    <span class="muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php echo e($paymentLinks->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
  function copyText(text) {
    if (navigator.clipboard && window.isSecureContext) {
      return navigator.clipboard.writeText(text);
    }
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.left = '-9999px';
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    document.body.removeChild(ta);
    return Promise.resolve();
  }
  function bindCopy(btn, getUrl) {
    btn.addEventListener('click', function () {
      var url = getUrl();
      if (!url) return;
      copyText(url).then(function () {
        var prev = btn.textContent;
        btn.textContent = 'تم النسخ';
        setTimeout(function () { btn.textContent = prev; }, 2000);
      });
    });
  }
  var copyCreated = document.getElementById('copy-created-url');
  if (copyCreated) {
    bindCopy(copyCreated, function () {
      var el = document.getElementById('created-payment-url');
      return el ? el.value : '';
    });
  }
  document.querySelectorAll('.copy-link-btn').forEach(function (btn) {
    bindCopy(btn, function () { return btn.getAttribute('data-url') || ''; });
  });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/admin/payment-links/index.blade.php ENDPATH**/ ?>