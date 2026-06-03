@php
    $categories = config('customer-form.item_categories');
    $referral = config('customer-form.referral_sources');
    $jPurposes = config('customer-form.jewelry.evaluation_purposes');
    $gPurposes = config('customer-form.gemstone.evaluation_purposes');
@endphp

<style>
.customer-modal { position: fixed; inset: 0; z-index: 100050; display: none; align-items: center; justify-content: center; padding: 1rem; box-sizing: border-box; }
.customer-modal.is-open { display: flex; }
.customer-modal__backdrop { position: absolute; inset: 0; background: rgba(26, 22, 18, 0.58); backdrop-filter: blur(3px); }
.customer-modal__dialog {
  position: relative; width: 100%; max-width: 540px; max-height: min(92vh, 720px); overflow: auto;
  background: linear-gradient(165deg, #fdfbf7 0%, #f7f2e8 100%);
  border: 1px solid rgba(201, 162, 39, 0.45); border-radius: 14px;
  box-shadow: 0 18px 48px rgba(30, 24, 16, 0.28); padding: 1.75rem 1.35rem 1.35rem;
  color: #1f1c18; font-family: Rubik, system-ui, sans-serif;
}
.customer-modal__close { position: absolute; top: 0.65rem; inset-inline-end: 0.65rem; width: 2.25rem; height: 2.25rem; border: none; border-radius: 50%; background: rgba(201, 162, 39, 0.18); color: #3d3429; font-size: 1.35rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.customer-modal__close:hover { background: rgba(201, 162, 39, 0.35); }
.customer-modal__title { font-size: 1.12rem; font-weight: 700; margin: 0 2rem 0.35rem 0; color: #2a231c; }
.customer-modal__subtitle { font-size: 0.85rem; margin: 0 0 1rem; color: #5c5349; line-height: 1.45; }
.customer-modal__service-badge { display: inline-block; margin-bottom: 1rem; padding: 0.35rem 0.65rem; border-radius: 8px; background: rgba(201, 162, 39, 0.15); font-size: 0.8rem; font-weight: 600; color: #5c4a1a; }
.customer-modal__section { margin-bottom: 1.1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e8dfd0; }
.customer-modal__section-title { font-size: 0.92rem; font-weight: 700; margin: 0 0 0.75rem; color: #3d3429; }
.customer-modal__field { margin-bottom: 0.85rem; }
.customer-modal__grid-2 { display: grid; gap: 0.75rem; }
@media (min-width: 480px) { .customer-modal__grid-2 { grid-template-columns: 1fr 1fr; } }
.customer-modal__label { display: block; font-size: 0.78rem; font-weight: 600; margin-bottom: 0.3rem; color: #3d3429; }
.customer-modal__req { color: #a67c00; }
.customer-modal__input, .customer-modal__textarea {
  width: 100%; padding: 0.55rem 0.7rem; border-radius: 9px; border: 1px solid #e4dac8;
  background: #fffdf9; color: #1f1c18; font-size: 0.9rem; box-sizing: border-box;
}
.customer-modal__textarea { min-height: 72px; resize: vertical; }
.customer-modal__input:focus, .customer-modal__textarea:focus { outline: none; border-color: rgba(201, 162, 39, 0.85); box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.2); }
.customer-modal__input.is-invalid { border-color: #c44; }
.customer-modal__checks { display: flex; flex-wrap: wrap; gap: 0.45rem 0.85rem; }
.customer-modal__check { display: flex; align-items: center; gap: 0.35rem; font-size: 0.82rem; cursor: pointer; }
.customer-modal__check input { accent-color: #b8922e; }
.customer-modal__type-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin: 1rem 0; }
.customer-modal__type-btn {
  padding: 1rem 0.75rem; border: 2px solid #e4dac8; border-radius: 12px; background: #fff;
  cursor: pointer; font-family: inherit; font-weight: 600; font-size: 0.95rem; color: #2a231c;
  transition: border-color 0.15s, background 0.15s;
}
.customer-modal__type-btn:hover, .customer-modal__type-btn.is-selected { border-color: #b8922e; background: rgba(201, 162, 39, 0.12); }
.customer-modal__type-btn small { display: block; font-weight: 400; font-size: 0.75rem; color: #5c5349; margin-top: 0.25rem; }
.customer-modal__step-form { display: none; }
.customer-modal__step-form.is-active { display: block; }
.customer-modal__step-pick.is-hidden { display: none; }
.customer-modal__actions { display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap; }
.customer-modal__submit, .customer-modal__btn-secondary {
  flex: 1; min-width: 120px; padding: 0.7rem 1rem; border-radius: 999px; font-weight: 700; font-size: 0.92rem; cursor: pointer; font-family: inherit;
}
.customer-modal__submit {
  border: none; background: linear-gradient(135deg, #d4b04a 0%, #b8922e 55%, #9a761f 100%); color: #fff;
  box-shadow: 0 6px 18px rgba(154, 118, 31, 0.35);
}
.customer-modal__submit:disabled { opacity: 0.65; cursor: wait; }
.customer-modal__btn-secondary { border: 1px solid #e4dac8; background: #fff; color: #3d3429; }
.customer-modal__errors { display: none; margin-bottom: 1rem; padding: 0.65rem 0.75rem; border-radius: 9px; background: rgba(196, 68, 68, 0.09); border: 1px solid rgba(196, 68, 68, 0.35); color: #7a1f1f; font-size: 0.82rem; white-space: pre-line; }
.customer-modal__errors.is-visible { display: block; }
.customer-modal__terms {
  display: flex;
  align-items: center;
  gap: 0.55rem;
  margin: 0.85rem 0 0.5rem;
  padding: 0.65rem 0.75rem;
  border-radius: 10px;
  background: rgba(201, 162, 39, 0.08);
  border: 1px solid rgba(201, 162, 39, 0.22);
  font-size: 0.84rem;
  line-height: 1.5;
  cursor: pointer;
  width: 100%;
  box-sizing: border-box;
}
.customer-modal__terms input[type="checkbox"] {
  flex-shrink: 0;
  width: 1.05rem;
  height: 1.05rem;
  margin: 0;
  accent-color: #b8922e;
  cursor: pointer;
}
.customer-modal__terms-text {
  flex: 1;
  min-width: 0;
  line-height: 1.55;
}
html[dir="rtl"] .customer-modal__terms {
  flex-direction: row-reverse;
  justify-content: flex-start;
  text-align: right;
}
html[dir="rtl"] .customer-modal__terms-text {
  text-align: right;
}
html[dir="ltr"] .customer-modal__terms {
  flex-direction: row;
  justify-content: flex-start;
  text-align: left;
}
html[dir="ltr"] .customer-modal__terms-text {
  text-align: left;
}
.customer-modal__file-hint { font-size: 0.75rem; color: #5c5349; margin-top: 0.2rem; }
html[dir="ltr"] .customer-modal__dialog { text-align: left; }
html[dir="rtl"] .customer-modal__dialog { text-align: right; }
</style>

<div id="customerModal" class="customer-modal" aria-hidden="true" data-store-url="{{ route('customers.store') }}">
    <div class="customer-modal__backdrop" data-customer-close></div>
    <div class="customer-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="customerModalTitle">
        <button type="button" class="customer-modal__close" data-customer-close aria-label="Close">&times;</button>

        <h3 class="customer-modal__title" id="customerModalTitle">
            <span data-lang="ar" data-lang-display="block">ابدأ رحلتك التقييمية</span>
            <span data-lang="en" data-lang-display="block">Start your evaluation journey</span>
        </h3>

        <div id="customerStepPick" class="customer-modal__step-pick">
            <p class="customer-modal__subtitle">
                <span data-lang="ar" data-lang-display="block">اختر نوع القطعة التي تريد تقييمها:</span>
                <span data-lang="en" data-lang-display="block">Choose the type of item you want evaluated:</span>
            </p>
            <div class="customer-modal__type-grid">
                <button type="button" class="customer-modal__type-btn" data-item-category="jewelry">
                    <span data-lang="ar" data-lang-display="block">{{ $categories['jewelry']['ar'] }}</span>
                    <span data-lang="en" data-lang-display="block">{{ $categories['jewelry']['en'] }}</span>
                    <small><span data-lang="ar" data-lang-display="block">خاتم، عقد، سوار…</span><span data-lang="en" data-lang-display="block">Ring, necklace, bracelet…</span></small>
                </button>
                <button type="button" class="customer-modal__type-btn" data-item-category="gemstone">
                    <span data-lang="ar" data-lang-display="block">{{ $categories['gemstone']['ar'] }}</span>
                    <span data-lang="en" data-lang-display="block">{{ $categories['gemstone']['en'] }}</span>
                    <small><span data-lang="ar" data-lang-display="block">ألماس، زمرد، ياقوت…</span><span data-lang="en" data-lang-display="block">Diamond, emerald, ruby…</span></small>
                </button>
            </div>
        </div>

        <div id="customerStepForm" class="customer-modal__step-form">
            <p id="customerServiceBadge" class="customer-modal__service-badge"></p>
            <div id="customerErrors" class="customer-modal__errors" role="alert"></div>

            <form id="customerForm" novalidate enctype="multipart/form-data">
                <input type="hidden" name="service_id" id="customer_service_id" value="">
                <input type="hidden" name="locale" id="customer_locale" value="ar">
                <input type="hidden" name="item_category" id="customer_item_category" value="">

                <div class="customer-modal__section">
                    <h4 class="customer-modal__section-title">
                        <span data-lang="ar" data-lang-display="block">البيانات الشخصية</span>
                        <span data-lang="en" data-lang-display="block">Personal information</span>
                    </h4>
                    <div class="customer-modal__field">
                        <label class="customer-modal__label" for="customer_name">
                            <span data-lang="ar" data-lang-display="inline">الاسم الكامل</span>
                            <span data-lang="en" data-lang-display="inline">Full name</span>
                            <span class="customer-modal__req"> *</span>
                        </label>
                        <input class="customer-modal__input" type="text" name="name" id="customer_name" required maxlength="255" autocomplete="name">
                    </div>
                    <div class="customer-modal__grid-2">
                        <div class="customer-modal__field">
                            <label class="customer-modal__label" for="customer_phone">
                                <span data-lang="ar" data-lang-display="inline">رقم الجوال</span>
                                <span data-lang="en" data-lang-display="inline">Mobile</span>
                                <span class="customer-modal__req"> *</span>
                            </label>
                            <input class="customer-modal__input" type="tel" name="phone" id="customer_phone" required maxlength="32" autocomplete="tel">
                        </div>
                        <div class="customer-modal__field">
                            <label class="customer-modal__label" for="customer_city">
                                <span data-lang="ar" data-lang-display="inline">المدينة</span>
                                <span data-lang="en" data-lang-display="inline">City</span>
                                <span class="customer-modal__req"> *</span>
                            </label>
                            <input class="customer-modal__input" type="text" name="city" id="customer_city" required maxlength="128">
                        </div>
                    </div>
                    <div class="customer-modal__field">
                        <label class="customer-modal__label" for="customer_email">
                            <span data-lang="ar" data-lang-display="inline">البريد الإلكتروني</span>
                            <span data-lang="en" data-lang-display="inline">Email</span>
                        </label>
                        <input class="customer-modal__input" type="email" name="email" id="customer_email" maxlength="255" autocomplete="email">
                    </div>
                </div>

                @include('partials.customer-form-jewelry')
                @include('partials.customer-form-gemstone')

                <div class="customer-modal__section">
                    <h4 class="customer-modal__section-title">
                        <span data-lang="ar" data-lang-display="block">الغرض من التقييم</span>
                        <span data-lang="en" data-lang-display="block">Purpose of evaluation</span>
                    </h4>
                    <div class="customer-modal__checks" data-purpose-group="jewelry">
                        @foreach ($jPurposes as $key => $labels)
                            <label class="customer-modal__check">
                                <input type="checkbox" name="evaluation_purpose[]" value="{{ $key }}">
                                <span data-lang="ar" data-lang-display="inline">{{ $labels['ar'] }}</span>
                                <span data-lang="en" data-lang-display="inline">{{ $labels['en'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="customer-modal__checks" data-purpose-group="gemstone" hidden>
                        @foreach ($gPurposes as $key => $labels)
                            <label class="customer-modal__check">
                                <input type="checkbox" name="evaluation_purpose[]" value="{{ $key }}">
                                <span data-lang="ar" data-lang-display="inline">{{ $labels['ar'] }}</span>
                                <span data-lang="en" data-lang-display="inline">{{ $labels['en'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="customer-modal__section">
                    <h4 class="customer-modal__section-title">
                        <span data-lang="ar" data-lang-display="block">المرفقات</span>
                        <span data-lang="en" data-lang-display="block">Attachments</span>
                    </h4>
                    <div class="customer-modal__field">
                        <label class="customer-modal__label">
                            <span data-lang="ar" data-lang-display="inline">صور القطعة</span>
                            <span data-lang="en" data-lang-display="inline">Photos</span>
                        </label>
                        <input class="customer-modal__input" type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple>
                        <p class="customer-modal__file-hint"><span data-lang="ar" data-lang-display="inline">حتى 8 صور (JPG, PNG)</span><span data-lang="en" data-lang-display="inline">Up to 8 images</span></p>
                    </div>
                    <div class="customer-modal__field">
                        <label class="customer-modal__label">
                            <span data-lang="ar" data-lang-display="inline">الفاتورة</span>
                            <span data-lang="en" data-lang-display="inline">Invoice</span>
                        </label>
                        <input class="customer-modal__input" type="file" name="invoice" accept="image/*,application/pdf">
                    </div>
                    <div class="customer-modal__field">
                        <label class="customer-modal__label">
                            <span data-lang="ar" data-lang-display="inline">الشهادات</span>
                            <span data-lang="en" data-lang-display="inline">Certificates</span>
                        </label>
                        <input class="customer-modal__input" type="file" name="certificates[]" accept="image/*,application/pdf" multiple>
                    </div>
                </div>

                <div class="customer-modal__section" style="border-bottom:none;">
                    <h4 class="customer-modal__section-title">
                        <span data-lang="ar" data-lang-display="block">معلومات إضافية</span>
                        <span data-lang="en" data-lang-display="block">Additional information</span>
                    </h4>
                    <div class="customer-modal__field">
                        <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">وصف مختصر</span><span data-lang="en" data-lang-display="inline">Brief description</span></label>
                        <textarea class="customer-modal__textarea" name="brief_description" maxlength="2000"></textarea>
                    </div>
                    <div class="customer-modal__field">
                        <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">ملاحظات إضافية</span><span data-lang="en" data-lang-display="inline">Additional notes</span></label>
                        <textarea class="customer-modal__textarea" name="additional_notes" maxlength="2000"></textarea>
                    </div>
                    <div class="customer-modal__field">
                        <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">كيف تعرفت علينا؟</span><span data-lang="en" data-lang-display="inline">How did you hear about us?</span></label>
                        <select class="customer-modal__input" name="referral_source">
                            <option value="">—</option>
                            @foreach ($referral as $key => $labels)
                                <option value="{{ $key }}" data-label-ar="{{ $labels['ar'] }}" data-label-en="{{ $labels['en'] }}">{{ $labels['ar'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <label class="customer-modal__terms">
                    <input type="checkbox" name="terms" value="1" required>
                    <span class="customer-modal__terms-text">
                        <span data-lang="ar" data-lang-display="inline">أوافق على الشروط&nbsp;والأحكام</span>
                        <span data-lang="en" data-lang-display="inline">I agree to the terms and conditions</span>
                    </span>
                </label>

                <div class="customer-modal__actions">
                    <button type="button" class="customer-modal__btn-secondary" id="customerBackBtn">
                        <span data-lang="ar" data-lang-display="inline">رجوع</span>
                        <span data-lang="en" data-lang-display="inline">Back</span>
                    </button>
                    <button type="submit" class="customer-modal__submit" id="customerSubmit">
                        <span data-lang="ar" data-lang-display="inline">ابدأ رحلتك التقييمية</span>
                        <span data-lang="en" data-lang-display="inline">Start your evaluation journey</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/customer-modal.js') }}" defer></script>
