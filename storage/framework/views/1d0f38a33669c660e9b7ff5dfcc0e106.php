<?php $__env->startSection('title', 'الدفع — Sultan Jewelry'); ?>

<?php $__env->startSection('content'); ?>
<section class="section section-padding-top-bottom" style="min-height: 50vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-6 text-center">
        <h1 class="section-title text-uppercase" style="margin-bottom: 1rem;">
          <span data-lang="ar">تعذّر إتمام الدفع</span>
          <span data-lang="en">Payment could not be completed</span>
        </h1>
        <?php if(($reason ?? '') === 'still_pending'): ?>
          <p class="muted" style="font-size: 1rem; line-height: 1.6;">
            <span data-lang="ar">
              لا يزال تحديث حالة الدفع قيد المعالجة في البوابة. قد تُحدَّث الصفحة تلقائياً خلال ثوانٍ؛ أو انتظر ثم حدّث يدوياً.
            </span>
            <span data-lang="en">
              Your payment is still being processed by the gateway. This page may refresh automatically in a few seconds.
            </span>
          </p>
        <?php else: ?>
          <p class="muted" style="font-size: 1rem; line-height: 1.6;">
            <span data-lang="ar">
              لم يكتمل الدفع أو تم إلغاؤه. يمكنك العودة للصفحة الرئيسية والمحاولة مرة أخرى عندما تكون جاهزًا.
            </span>
            <span data-lang="en">
              Your payment was not completed or was cancelled. You can return home and try again when you are ready.
            </span>
          </p>
        <?php endif; ?>
        <?php if(! empty($invoice_status)): ?>
          <p class="muted" style="font-size: 0.9rem; margin-top: 0.75rem;">
            <span data-lang="ar">حالة الفاتورة من البوابة: <strong><?php echo e($invoice_status); ?></strong></span>
            <span data-lang="en">Gateway invoice status: <strong><?php echo e($invoice_status); ?></strong></span>
          </p>
        <?php endif; ?>
        <?php if(! empty($gateway_message)): ?>
          <p class="muted" style="font-size: 0.85rem; margin-top: 0.5rem;" dir="ltr"><?php echo e($gateway_message); ?></p>
        <?php endif; ?>
        <p style="margin-top: 1.25rem;">
          <a href="<?php echo e(route('home')); ?>" class="btn-primary">
            <div class="btn-wrapper" style="padding: 0.55rem 1.35rem;">
              <span data-lang="ar" data-lang-display="inline" class="btn-text">الصفحة الرئيسية</span>
              <span data-lang="en" data-lang-display="inline" class="btn-text">Home</span>
              <span class="btn-icons">
                <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
              </span>
            </div>
          </a>
        </p>
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
  let currentLang = localStorage.getItem("siteLang") || "ar";
  function applyLanguage(lang) {
    const html = document.documentElement;
    html.lang = lang;
    html.dir = lang === "ar" ? "rtl" : "ltr";
    html.classList.remove("lang-ar", "lang-en");
    html.classList.add(lang === "ar" ? "lang-ar" : "lang-en");
    document.querySelectorAll("[data-lang]").forEach(function (el) {
      const mode = el.getAttribute("data-lang-display") || "inline";
      el.style.display = el.getAttribute("data-lang") === lang ? mode : "none";
    });
    localStorage.setItem("siteLang", lang);
    currentLang = lang;
  }
  applyLanguage(currentLang);
  document.querySelectorAll(".lang-btn").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      applyLanguage(currentLang === "ar" ? "en" : "ar");
    });
  });
})();
</script>
<?php if(filled($paymentId ?? null)): ?>
  <?php $invoiceStatusNorm = strtolower(trim((string) ($invoice_status ?? ''))); ?>
  <?php if($invoiceStatusNorm === 'pending' || ($reason ?? '') === 'still_pending'): ?>
<script>
(function () {
  var pid = new URLSearchParams(location.search).get("paymentId") || "";
  if (!pid) return;
  var k = "mfPendingPoll_" + pid;
  var n = parseInt(sessionStorage.getItem(k) || "0", 10);
  if (n >= 18) return;
  sessionStorage.setItem(k, String(n + 1));
  setTimeout(function () { location.reload(); }, 2500);
})();
</script>
  <?php endif; ?>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/payment/error.blade.php ENDPATH**/ ?>