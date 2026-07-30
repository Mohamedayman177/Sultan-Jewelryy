(function () {
  const modal = document.getElementById("customerModal");
  const form = document.getElementById("customerForm");
  if (!modal || !form) return;

  const storeUrl = modal.dataset.storeUrl;
  const stepPick = document.getElementById("customerStepPick");
  const stepForm = document.getElementById("customerStepForm");
  const serviceInput = document.getElementById("customer_service_id");
  const categoryInput = document.getElementById("customer_item_category");
  const localeInput = document.getElementById("customer_locale");
  const serviceBadge = document.getElementById("customerServiceBadge");
  const errBox = document.getElementById("customerErrors");
  const submitBtn = document.getElementById("customerSubmit");
  const backBtn = document.getElementById("customerBackBtn");

  let currentLang = localStorage.getItem("siteLang") || "ar";
  let selectedCategory = "";

  function csrfToken() {
    const m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.getAttribute("content") : "";
  }

  function applySelectOptions(lang) {
    form.querySelectorAll("select.customer-modal__input option[data-label-ar]").forEach(function (opt) {
      if (!opt.value) return;
      const ar = opt.getAttribute("data-label-ar");
      const en = opt.getAttribute("data-label-en");
      opt.textContent = lang === "en" ? en || ar : ar || en;
    });
  }

  function showCategoryPanels(category) {
    form.querySelectorAll("[data-category-panel]").forEach(function (panel) {
      const active = panel.getAttribute("data-category-panel") === category;
      panel.style.display = active ? "block" : "none";
      panel.querySelectorAll("input, select, textarea").forEach(function (el) {
        el.disabled = !active;
      });
    });
    form.querySelectorAll("[data-purpose-group]").forEach(function (group) {
      const match = group.getAttribute("data-purpose-group") === category;
      group.hidden = !match;
      if (!match) {
        group.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
          cb.checked = false;
        });
      }
    });
  }

  function openModal(serviceId, serviceTitleAr, serviceTitleEn) {
    form.reset();
    selectedCategory = "";
    categoryInput.value = "";
    serviceInput.value = serviceId || "";
    localeInput.value = currentLang;
    errBox.textContent = "";
    errBox.classList.remove("is-visible");
    form.querySelectorAll(".customer-modal__input, .customer-modal__textarea").forEach(function (i) {
      i.classList.remove("is-invalid");
    });
    document.querySelectorAll(".customer-modal__type-btn").forEach(function (b) {
      b.classList.remove("is-selected");
    });

    const ar = serviceTitleAr || "";
    const en = serviceTitleEn || "";
    serviceBadge.textContent =
      currentLang === "en" ? (en || ar) : (ar || en);
    serviceBadge.style.display = ar || en ? "inline-block" : "none";

    stepPick.classList.remove("is-hidden");
    stepForm.classList.remove("is-active");
    showCategoryPanels("jewelry");

    if (typeof window.applyLanguage === "function") {
      window.applyLanguage(currentLang);
    }
    applySelectOptions(currentLang);

    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
  }

  function closeModal() {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }

  function goToForm(category) {
    selectedCategory = category;
    categoryInput.value = category;
    showCategoryPanels(category);
    stepPick.classList.add("is-hidden");
    stepForm.classList.add("is-active");
    if (typeof window.applyLanguage === "function") {
      window.applyLanguage(currentLang);
    }
    applySelectOptions(currentLang);
  }

  document.querySelectorAll(".customer-modal-open").forEach(function (el) {
    el.addEventListener("click", function (e) {
      e.preventDefault();
      openModal(
        this.dataset.serviceId,
        this.dataset.serviceTitleAr,
        this.dataset.serviceTitleEn
      );
    });
  });

  document.querySelectorAll(".customer-modal__type-btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
      document.querySelectorAll(".customer-modal__type-btn").forEach(function (b) {
        b.classList.remove("is-selected");
      });
      btn.classList.add("is-selected");
      goToForm(btn.getAttribute("data-item-category"));
    });
  });

  if (backBtn) {
    backBtn.addEventListener("click", function () {
      stepForm.classList.remove("is-active");
      stepPick.classList.remove("is-hidden");
    });
  }

  modal.querySelectorAll("[data-customer-close]").forEach(function (el) {
    el.addEventListener("click", closeModal);
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && modal.classList.contains("is-open")) closeModal();
  });

  document.addEventListener("siteLangChanged", function (e) {
    currentLang = e.detail && e.detail.lang ? e.detail.lang : currentLang;
    localeInput.value = currentLang;
    applySelectOptions(currentLang);
  });

  function renderErrors(errors) {
    const lines = [];
    Object.keys(errors).forEach(function (k) {
      (errors[k] || []).forEach(function (msg) {
        lines.push(msg);
      });
    });
    errBox.textContent =
      lines.join("\n") ||
      (currentLang === "ar" ? "تعذر إرسال الطلب." : "Could not submit the form.");
    errBox.classList.add("is-visible");
  }

  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    if (!categoryInput.value) {
      renderErrors({
        item_category: [
          currentLang === "ar"
            ? "يرجى اختيار نوع القطعة أولاً."
            : "Please choose jewelry or gemstones first.",
        ],
      });
      return;
    }

    errBox.classList.remove("is-visible");
    errBox.textContent = "";
    localeInput.value = currentLang;

    const fd = new FormData(form);
    fd.set("_token", csrfToken());
    fd.set("locale", currentLang);

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
      const data = await res.json().catch(function () {
        return {};
      });
      if (res.status === 422 && data.errors) {
        renderErrors(data.errors);
        return;
      }
      if (!res.ok || !data.whatsapp_url) {
        renderErrors({
          phone: [
            data.message ||
              (currentLang === "ar" ? "حدث خطأ، حاول لاحقًا." : "Something went wrong."),
          ],
        });
        return;
      }
      closeModal();
      window.open(data.whatsapp_url, "_blank", "noopener,noreferrer");
    } catch {
      renderErrors({
        phone: [
          currentLang === "ar" ? "تعذر الاتصال بالخادم." : "Could not reach the server.",
        ],
      });
    } finally {
      submitBtn.disabled = false;
    }
  });

  window.customerModalRefreshLang = function (lang) {
    currentLang = lang;
    applySelectOptions(lang);
  };
})();
