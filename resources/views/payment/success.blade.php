@extends('layouts.site')

@section('title', 'الدفع — Sultan Jewelry')

@section('content')
<section class="section section-padding-top-bottom" style="min-height: 50vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-6 text-center">
        <h1 class="section-title text-uppercase" style="margin-bottom: 1rem;">
          <span data-lang="ar">تم استلام الدفع بنجاح</span>
          <span data-lang="en">Payment received successfully</span>
        </h1>
        <p class="muted" style="font-size: 1rem; line-height: 1.6;">
          <span data-lang="ar">شكراً لك. تم تأكيد عملية الدفع.</span>
          <span data-lang="en">Thank you. Your payment has been confirmed.</span>
        </p>
        <p style="margin-top: 1.25rem;">
          <a href="{{ route('home') }}" class="btn-primary">
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
@endsection

@push('scripts')
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
@endpush
