<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('sections.home-main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
    if (typeof window.customerModalRefreshLang === "function") {
      window.customerModalRefreshLang(lang);
    }
    document.dispatchEvent(new CustomEvent("siteLangChanged", { detail: { lang } }));
  }

  window.applyLanguage = applyLanguage;
  applyLanguage(currentLang);

  document.querySelectorAll(".lang-btn").forEach(btn => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      applyLanguage(currentLang === "ar" ? "en" : "ar");
    });
  });
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/pages/home.blade.php ENDPATH**/ ?>