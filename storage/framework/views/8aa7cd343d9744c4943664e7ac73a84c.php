
<?php
    $fadeDelays = ['0.30', '0.45', '0.60', '0.30'];
?>

<section class="pricing section section-padding-top-bottom" id="الخدمات">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-header text-center row-padding-bottom">
          <h2 class="section-title text-uppercase">
            <span data-lang="ar">الخدمات التي نقدمها</span>
            <span data-lang="en">The Services We Offer</span>
          </h2>
        </div>
      </div>
    </div>

    <div class="row g-4 align-items-stretch">
      <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-lg-6 col-xxl-3">
          <div class="pricing-box fade-anim" data-delay="<?php echo e($fadeDelays[$loop->index % count($fadeDelays)]); ?>">
            <div class="pricing-header">
              <div class="header-info">
                <h4 class="plan-type">
                  <span data-lang="ar"><?php echo e($service->title_ar); ?></span>
                  <span data-lang="en"><?php echo e($service->title_en); ?></span>
                </h4>
                <?php if($service->is_free): ?>
                  <h4 class="plan-price">
                    <span data-lang="ar">مجانًا</span>
                    <span data-lang="en">Free</span>
                  </h4>
                <?php else: ?>
                  <h4 class="plan-price"><?php echo e(number_format((float) $service->price, 0)); ?> SAR</h4>
                <?php endif; ?>
              </div>
            </div>

            <div class="pricing-body">
              <ul>
                <li>
                  <span data-lang="ar"><?php echo e($service->description_ar); ?></span>
                  <span data-lang="en"><?php echo e($service->description_en); ?></span>
                </li>
              </ul>
            </div>

            <?php
              $ctaAr = $service->is_free ? 'جلسة تعريفية' : 'ابدأ رحلتك التقييمية';
              $ctaEn = $service->is_free ? 'Introductory Session' : 'Start Your Evaluation Journey';
            ?>

            <div class="pricing-footer text-center">
              <?php if($service->requires_registration): ?>
                <a href="#" role="button" class="btn-primary customer-modal-open"
                   data-service-id="<?php echo e($service->id); ?>"
                   data-service-title-ar="<?php echo e($service->title_ar); ?>"
                   data-service-title-en="<?php echo e($service->title_en); ?>">
                  <div class="btn-wrapper">
                    <span data-lang="ar" data-lang-display="inline" class="btn-text"><?php echo e($ctaAr); ?></span>
                    <span data-lang="en" data-lang-display="inline" class="btn-text"><?php echo e($ctaEn); ?></span>
                    <span class="btn-icons">
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                    </span>
                  </div>
                </a>
              <?php else: ?>
                <a href="<?php echo e($whatsappBaseUrl); ?>" target="_blank" rel="noopener noreferrer" class="btn-primary">
                  <div class="btn-wrapper">
                    <span data-lang="ar" data-lang-display="inline" class="btn-text"><?php echo e($ctaAr); ?></span>
                    <span data-lang="en" data-lang-display="inline" class="btn-text"><?php echo e($ctaEn); ?></span>
                    <span class="btn-icons">
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                    </span>
                  </div>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12 text-center">
          <p class="muted">لا توجد خدمات معروضة حالياً.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/sections/pricing-services.blade.php ENDPATH**/ ?>