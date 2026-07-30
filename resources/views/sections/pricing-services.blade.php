{{-- Pricing cards driven by admin-managed services ($services, $whatsappBaseUrl from HomeController) --}}
@php
    $fadeDelays = ['0.30', '0.45', '0.60', '0.30'];
@endphp

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
      @forelse ($services as $service)
        <div class="col-lg-6 col-xxl-3">
          <div class="pricing-box fade-anim" data-delay="{{ $fadeDelays[$loop->index % count($fadeDelays)] }}">
            <div class="pricing-header">
              <div class="header-info">
                <h4 class="plan-type">
                  <span data-lang="ar">{{ $service->title_ar }}</span>
                  <span data-lang="en">{{ $service->title_en }}</span>
                </h4>
              </div>
            </div>

            <div class="pricing-body">
              <ul>
                <li>
                  <span data-lang="ar">{{ $service->description_ar }}</span>
                  <span data-lang="en">{{ $service->description_en }}</span>
                </li>
              </ul>
            </div>

            @php
              $ctaAr = $service->is_free ? 'جلسة تعريفية' : 'ابدأ رحلتك التقييمية';
              $ctaEn = $service->is_free ? 'Introductory Session' : 'Start Your Evaluation Journey';
            @endphp

            <div class="pricing-footer text-center">
              @if ($service->requires_registration)
                <a href="#" role="button" class="btn-primary customer-modal-open"
                   data-service-id="{{ $service->id }}"
                   data-service-title-ar="{{ $service->title_ar }}"
                   data-service-title-en="{{ $service->title_en }}">
                  <div class="btn-wrapper">
                    <span data-lang="ar" data-lang-display="inline" class="btn-text">{{ $ctaAr }}</span>
                    <span data-lang="en" data-lang-display="inline" class="btn-text">{{ $ctaEn }}</span>
                    <span class="btn-icons">
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                    </span>
                  </div>
                </a>
              @else
                <a href="{{ $whatsappBaseUrl }}" target="_blank" rel="noopener noreferrer" class="btn-primary">
                  <div class="btn-wrapper">
                    <span data-lang="ar" data-lang-display="inline" class="btn-text">{{ $ctaAr }}</span>
                    <span data-lang="en" data-lang-display="inline" class="btn-text">{{ $ctaEn }}</span>
                    <span class="btn-icons">
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                      <i class="fa-sharp fa-solid fa-arrow-up-right"></i>
                    </span>
                  </div>
                </a>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center">
          <p class="muted">لا توجد خدمات معروضة حالياً.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>
