{{-- Customer registration before WhatsApp (paid service cards) --}}
<style>
.customer-modal {
  position: fixed;
  inset: 0;
  z-index: 100050;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  box-sizing: border-box;
}
.customer-modal.is-open {
  display: flex;
}
.customer-modal__backdrop {
  position: absolute;
  inset: 0;
  background: rgba(26, 22, 18, 0.58);
  backdrop-filter: blur(3px);
}
.customer-modal__dialog {
  position: relative;
  width: 100%;
  max-width: 440px;
  max-height: min(92vh, 640px);
  overflow: auto;
  background: linear-gradient(165deg, #fdfbf7 0%, #f7f2e8 100%);
  border: 1px solid rgba(201, 162, 39, 0.45);
  border-radius: 14px;
  box-shadow: 0 18px 48px rgba(30, 24, 16, 0.28), 0 0 0 1px rgba(255, 255, 255, 0.35) inset;
  padding: 1.75rem 1.5rem 1.5rem;
  color: #1f1c18;
  font-family: Rubik, system-ui, sans-serif;
}
.customer-modal__close {
  position: absolute;
  top: 0.65rem;
  inset-inline-end: 0.65rem;
  width: 2.25rem;
  height: 2.25rem;
  border: none;
  border-radius: 50%;
  background: rgba(201, 162, 39, 0.18);
  color: #3d3429;
  font-size: 1.35rem;
  line-height: 1;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.15s ease;
}
.customer-modal__close:hover {
  background: rgba(201, 162, 39, 0.35);
}
.customer-modal__title {
  font-size: 1.15rem;
  font-weight: 700;
  margin: 0 2rem 0.35rem 0;
  color: #2a231c;
  line-height: 1.35;
}
.customer-modal__subtitle {
  font-size: 0.875rem;
  margin: 0 0 1.25rem;
  color: #5c5349;
  line-height: 1.45;
}
.customer-modal__field {
  margin-bottom: 1rem;
}
.customer-modal__label {
  display: block;
  font-size: 0.8rem;
  font-weight: 600;
  margin-bottom: 0.35rem;
  color: #3d3429;
}
.customer-modal__req {
  color: #a67c00;
}
.customer-modal__input {
  width: 100%;
  padding: 0.58rem 0.75rem;
  border-radius: 9px;
  border: 1px solid #e4dac8;
  background: #fffdf9;
  color: #1f1c18;
  font-size: 0.92rem;
  box-sizing: border-box;
  transition: border-color 0.15s, box-shadow 0.15s;
}
.customer-modal__input:focus {
  outline: none;
  border-color: rgba(201, 162, 39, 0.85);
  box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.22);
}
.customer-modal__input.is-invalid {
  border-color: #c44;
}
.customer-modal__submit {
  width: 100%;
  margin-top: 0.25rem;
  padding: 0.72rem 1rem;
  border: none;
  border-radius: 999px;
  cursor: pointer;
  font-weight: 700;
  font-size: 0.95rem;
  background: linear-gradient(135deg, #d4b04a 0%, #b8922e 55%, #9a761f 100%);
  color: #fff;
  box-shadow: 0 6px 18px rgba(154, 118, 31, 0.35);
  transition: transform 0.12s ease, box-shadow 0.12s ease;
}
.customer-modal__submit:hover {
  transform: translateY(-1px);
  box-shadow: 0 8px 22px rgba(154, 118, 31, 0.42);
}
.customer-modal__submit:disabled {
  opacity: 0.65;
  cursor: wait;
  transform: none;
}
.customer-modal__errors {
  display: none;
  margin-bottom: 1rem;
  padding: 0.65rem 0.75rem;
  border-radius: 9px;
  background: rgba(196, 68, 68, 0.09);
  border: 1px solid rgba(196, 68, 68, 0.35);
  color: #7a1f1f;
  font-size: 0.82rem;
  line-height: 1.45;
}
.customer-modal__errors.is-visible {
  display: block;
}
html[dir="ltr"] .customer-modal__dialog {
  text-align: left;
}
html[dir="rtl"] .customer-modal__dialog {
  text-align: right;
}
</style>

<div
    id="customerModal"
    class="customer-modal"
    aria-hidden="true"
    data-store-url="{{ route('customers.store') }}"
>
    <div class="customer-modal__backdrop" data-customer-close></div>

    <div
        class="customer-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="customerModalTitle"
    >
        <button type="button" class="customer-modal__close" data-customer-close aria-label="Close">&times;</button>

        <h3 class="customer-modal__title" id="customerModalTitle">
            <span data-lang="ar" data-lang-display="block">ابدأ رحلتك التقييمية</span>
            <span data-lang="en" data-lang-display="block">Start your evaluation journey</span>
        </h3>
        <p class="customer-modal__subtitle">
            <span data-lang="ar" data-lang-display="block">يرجى تعبئة البيانات التالية.</span>
            <span data-lang="en" data-lang-display="block">Please fill in your details.</span>
        </p>

        <div id="customerErrors" class="customer-modal__errors" role="alert"></div>

        <form id="customerForm" novalidate>
            <input type="hidden" name="service_key" id="customer_service_key" value="">
            <input type="hidden" name="locale" id="customer_locale" value="ar">

            <div class="customer-modal__field">
                <label class="customer-modal__label" for="customer_name">
                    <span data-lang="ar" data-lang-display="inline">الاسم الكامل</span>
                    <span data-lang="en" data-lang-display="inline">Full name</span>
                </label>
                <input class="customer-modal__input" type="text" name="name" id="customer_name" autocomplete="name" maxlength="255">
            </div>

            <div class="customer-modal__field">
                <label class="customer-modal__label" for="customer_national_id">
                    <span data-lang="ar" data-lang-display="inline">رقم الهوية</span>
                    <span data-lang="en" data-lang-display="inline">National ID</span>
                </label>
                <input class="customer-modal__input" type="text" name="national_id" id="customer_national_id" autocomplete="off" maxlength="64">
            </div>

            <div class="customer-modal__field">
                <label class="customer-modal__label" for="customer_phone">
                    <span data-lang="ar" data-lang-display="inline">رقم الجوال</span>
                    <span data-lang="en" data-lang-display="inline">Mobile number</span>
                    <span class="customer-modal__req"> *</span>
                </label>
                <input class="customer-modal__input" type="tel" name="phone" id="customer_phone" autocomplete="tel" required maxlength="32">
            </div>

            <div class="customer-modal__field">
                <label class="customer-modal__label" for="customer_email">
                    <span data-lang="ar" data-lang-display="inline">البريد الإلكتروني</span>
                    <span data-lang="en" data-lang-display="inline">Email</span>
                </label>
                <input class="customer-modal__input" type="email" name="email" id="customer_email" autocomplete="email" maxlength="255">
            </div>

            <button type="submit" class="customer-modal__submit" id="customerSubmit">
                <span data-lang="ar" data-lang-display="inline">تأكيد</span>
                <span data-lang="en" data-lang-display="inline">Confirm</span>
            </button>
        </form>
    </div>
</div>
