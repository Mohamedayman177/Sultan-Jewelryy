@php
    $g = config('customer-form.gemstone');
    $yn = $g['yes_no_unknown'];
@endphp

<div class="customer-modal__section" data-category-panel="gemstone">
    <h4 class="customer-modal__section-title">
        <span data-lang="ar" data-lang-display="block">تفاصيل الحجر الكريم</span>
        <span data-lang="en" data-lang-display="block">Gemstone details</span>
    </h4>

    <div class="customer-modal__field">
        <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">نوع الحجر</span><span data-lang="en" data-lang-display="inline">Gemstone type</span></label>
        <select class="customer-modal__input" name="gemstone_type">
            <option value="">—</option>
            @foreach ($g['gemstone_types'] as $key => $labels)
                <option value="{{ $key }}" data-label-ar="{{ $labels['ar'] }}" data-label-en="{{ $labels['en'] }}">{{ $labels['ar'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">عدد الأحجار</span><span data-lang="en" data-lang-display="inline">Stones count</span></label>
            <input class="customer-modal__input" type="number" name="stones_count" min="1" max="999">
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">الوزن (قيراط)</span><span data-lang="en" data-lang-display="inline">Weight (ct)</span></label>
            <input class="customer-modal__input" type="text" name="approximate_weight_carat" maxlength="64">
        </div>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">الشكل</span><span data-lang="en" data-lang-display="inline">Shape</span></label>
            <select class="customer-modal__input" name="shape">
                <option value="">—</option>
                @foreach ($g['shapes'] as $key => $labels)
                    <option value="{{ $key }}" data-label-ar="{{ $labels['ar'] }}" data-label-en="{{ $labels['en'] }}">{{ $labels['ar'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">اللون</span><span data-lang="en" data-lang-display="inline">Color</span></label>
            <input class="customer-modal__input" type="text" name="color" maxlength="64">
        </div>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">درجة النقاء</span><span data-lang="en" data-lang-display="inline">Clarity</span></label>
            <input class="customer-modal__input" type="text" name="clarity_grade" maxlength="64">
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">طبيعي أم مصنع؟</span><span data-lang="en" data-lang-display="inline">Natural / lab?</span></label>
            <select class="customer-modal__input" name="origin_type">
                <option value="">—</option>
                @foreach ($g['origin_types'] as $key => $labels)
                    <option value="{{ $key }}" data-label-ar="{{ $labels['ar'] }}" data-label-en="{{ $labels['en'] }}">{{ $labels['ar'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">معالجة؟</span><span data-lang="en" data-lang-display="inline">Treated?</span></label>
            <select class="customer-modal__input" name="treated">@include('partials.customer-form-yes-no', ['options' => $yn])</select>
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">بلد المنشأ</span><span data-lang="en" data-lang-display="inline">Country of origin</span></label>
            <input class="customer-modal__input" type="text" name="country_of_origin" maxlength="128">
        </div>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">شهادة؟</span><span data-lang="en" data-lang-display="inline">Certificate?</span></label>
            <select class="customer-modal__input" name="has_certificate">@include('partials.customer-form-yes-no', ['options' => $yn])</select>
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">حالة الحجر</span><span data-lang="en" data-lang-display="inline">Stone condition</span></label>
            <select class="customer-modal__input" name="stone_condition">
                <option value="">—</option>
                @foreach ($g['stone_conditions'] as $key => $labels)
                    <option value="{{ $key }}" data-label-ar="{{ $labels['ar'] }}" data-label-en="{{ $labels['en'] }}">{{ $labels['ar'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
