@extends('layouts.site')

@section('content')
    @include('sections.home-main')
@endsection

@push('scripts')
<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
<script type="text/javascript">
new TradingView.widget({
  "width": "100%",
  "height": "100%",
  "symbol": "XAUUSD",
  "interval": "D",
  "timezone": "Etc/UTC",
  "theme": "Light",
  "style": "1",
  "locale": "en",
  "container_id": "goldChart",
  "autosize": true,
  "toolbar_bg": "#fff",
  "hide_side_toolbar": false,
  "withdateranges": true,
  "allow_symbol_change": true
});
</script>
<script>
function toArabicNumbers(num) {
  return num.toString().replace(/\d/g, d => '٠١٢٣٤٥٦٧٨٩'[d]);
}

setTimeout(() => {
  document.querySelectorAll('.arabic-number').forEach(el => {
    el.innerHTML = toArabicNumbers(el.innerText);
  });
}, 1000);
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const openVideo = document.getElementById('openVideo');
  const modal = document.getElementById('videoModal');
  const iframe = document.getElementById('youtubeFrame');
  const closeBtn = modal ? modal.querySelector('.close') : null;

  if (!openVideo || !modal || !iframe || !closeBtn) {
    return;
  }

  openVideo.addEventListener('click', function (e) {
    e.preventDefault();
    const url = new URL(this.href);
    const videoId = url.searchParams.get('v');
    iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
    modal.style.display = 'block';
  });

  closeBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', e => {
    if (e.target === modal) closeModal();
  });

  function closeModal() {
    modal.style.display = 'none';
    iframe.src = '';
  }
});
</script>
<script>
  let currentLang = localStorage.getItem("siteLang") || "ar";

  function applyLanguage(lang) {
    const html = document.documentElement;

    html.lang = lang;
    html.dir = lang === "ar" ? "rtl" : "ltr";

    html.classList.remove("lang-ar", "lang-en");
    html.classList.add(lang === "ar" ? "lang-ar" : "lang-en");

    document.querySelectorAll("[data-lang]").forEach(el => {
      const mode = el.getAttribute("data-lang-display") || "inline";
      el.style.display = el.getAttribute("data-lang") === lang ? mode : "none";
    });

    localStorage.setItem("siteLang", lang);
    currentLang = lang;
  }

  applyLanguage(currentLang);

  document.querySelectorAll(".lang-btn").forEach(btn => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      applyLanguage(currentLang === "ar" ? "en" : "ar");
    });
  });

  (function initCustomerModal() {
    const modal = document.getElementById("customerModal");
    const form = document.getElementById("customerForm");
    if (!modal || !form) return;

    const storeUrl = modal.dataset.storeUrl;
    const serviceInput = document.getElementById("customer_service_id");
    const localeInput = document.getElementById("customer_locale");
    const errBox = document.getElementById("customerErrors");
    const submitBtn = document.getElementById("customerSubmit");

    function csrfToken() {
      const m = document.querySelector('meta[name="csrf-token"]');
      return m ? m.getAttribute("content") : "";
    }

    function openModal(serviceId) {
      form.reset();
      serviceInput.value = serviceId || "";
      localeInput.value = currentLang;
      errBox.textContent = "";
      errBox.classList.remove("is-visible");
      form.querySelectorAll(".customer-modal__input").forEach(i => i.classList.remove("is-invalid"));
      applyLanguage(currentLang);
      modal.classList.add("is-open");
      modal.setAttribute("aria-hidden", "false");
      document.body.style.overflow = "hidden";
    }

    function closeModal() {
      modal.classList.remove("is-open");
      modal.setAttribute("aria-hidden", "true");
      document.body.style.overflow = "";
    }

    document.querySelectorAll(".customer-modal-open").forEach(el => {
      el.addEventListener("click", function (e) {
        e.preventDefault();
        openModal(this.dataset.serviceId);
      });
    });

    modal.querySelectorAll("[data-customer-close]").forEach(el => {
      el.addEventListener("click", closeModal);
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && modal.classList.contains("is-open")) closeModal();
    });

    function renderErrors(errors) {
      const lines = [];
      const keys = ["phone", "name", "national_id", "email", "service_id", "locale"];
      keys.forEach(k => {
        if (!errors[k]) return;
        errors[k].forEach(msg => lines.push(msg));
      });
      Object.keys(errors).forEach(k => {
        if (keys.includes(k)) return;
        errors[k].forEach(msg => lines.push(msg));
      });
      errBox.textContent = lines.join("\n") || (currentLang === "ar" ? "تعذر إرسال الطلب." : "Could not submit the form.");
      errBox.classList.add("is-visible");
    }

    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      errBox.classList.remove("is-visible");
      errBox.textContent = "";
      localeInput.value = currentLang;

      const fd = new FormData(form);
      fd.set("_token", csrfToken());

      submitBtn.disabled = true;
      try {
        const res = await fetch(storeUrl, {
          method: "POST",
          headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": csrfToken(),
            "X-Requested-With": "XMLHttpRequest",
          },
          body: fd,
        });
        const data = await res.json().catch(() => ({}));
        if (res.status === 422 && data.errors) {
          renderErrors(data.errors);
          return;
        }
        if (data.payment_url) {
          closeModal();
          window.location.assign(data.payment_url);
          return;
        }
        if (!res.ok || !data.whatsapp_url) {
          const fallback =
            data.message ||
            (currentLang === "ar" ? "حدث خطأ، حاول لاحقًا." : "Something went wrong. Try again.");
          let detail = fallback;
          if (data.gateway_message) {
            detail = fallback + "\n" + data.gateway_message;
          }
          renderErrors({ phone: [detail] });
          return;
        }
        closeModal();
        window.open(data.whatsapp_url, "_blank", "noopener,noreferrer");
      } catch {
        renderErrors({
          phone: [currentLang === "ar" ? "تعذر الاتصال بالخادم." : "Could not reach the server."],
        });
      } finally {
        submitBtn.disabled = false;
      }
    });
  })();
</script>
<script>
new Swiper(".aboutSwiper", {
  loop: true,
  effect: "fade",
  speed: 2000,
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  fadeEffect: {
    crossFade: true,
  },
});
</script>
@endpush
