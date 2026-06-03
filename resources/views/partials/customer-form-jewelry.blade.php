@php
    $j = config('customer-form.jewelry');
    $yn = $j['yes_no_unknown'];
@endphp

<div class="customer-modal__section" data-category-panel="jewelry">
    <h4 class="customer-modal__section-title">
        <span data-lang="ar" data-lang-display="block">تفاصيل المجوهرات</span>
        <span data-lang="en" data-lang-display="block">Jewelry details</span>
    </h4>

    <div class="customer-modal__field">
        <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">نوع القطعة</span><span data-lang="en" data-lang-display="inline">Piece type</span></label>
        <select class="customer-modal__input" name="piece_type">
            <option value="">—</option>
            @foreach ($j['piece_types'] as $key => $labels)
                <option value="{{ $key }}" data-label-ar="{{ $labels['ar'] }}" data-label-en="{{ $labels['en'] }}">{{ $labels['ar'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="customer-modal__field">
        <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">نوع المعدن</span><span data-lang="en" data-lang-display="inline">Metal type</span></label>
        <select class="customer-modal__input" name="metal_type">
            <option value="">—</option>
            @foreach ($j['metal_types'] as $key => $labels)
                <option value="{{ $key }}" data-label-ar="{{ $labels['ar'] }}" data-label-en="{{ $labels['en'] }}">{{ $labels['ar'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">عدد القطع</span><span data-lang="en" data-lang-display="inline">Pieces count</span></label>
            <input class="customer-modal__input" type="number" name="pieces_count" min="1" max="999">
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">الوزن التقريبي</span><span data-lang="en" data-lang-display="inline">Approx. weight</span></label>
            <input class="customer-modal__input" type="text" name="approximate_weight" maxlength="64" placeholder="مثال: 15 جرام">
        </div>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">العيار</span><span data-lang="en" data-lang-display="inline">Karat</span></label>
            <input class="customer-modal__input" type="text" name="karat" maxlength="32" placeholder="18">
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">الماركة</span><span data-lang="en" data-lang-display="inline">Brand</span></label>
            <input class="customer-modal__input" type="text" name="brand" maxlength="128">
        </div>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">دمغة أو ختم؟</span><span data-lang="en" data-lang-display="inline">Hallmark?</span></label>
            <select class="customer-modal__input" name="has_hallmark">@include('partials.customer-form-yes-no', ['options' => $yn])</select>
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">فاتورة؟</span><span data-lang="en" data-lang-display="inline">Invoice?</span></label>
            <select class="customer-modal__input" name="has_invoice">@include('partials.customer-form-yes-no', ['options' => $yn])</select>
        </div>
    </div>

    <div class="customer-modal__grid-2">
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">شهادة؟</span><span data-lang="en" data-lang-display="inline">Certificate?</span></label>
            <select class="customer-modal__input" name="has_certificate">@include('partials.customer-form-yes-no', ['options' => $yn])</select>
        </div>
        <div class="customer-modal__field">
            <label class="customer-modal__label"><span data-lang="ar" data-lang-display="inline">حالة القطعة</span><span data-lang="en" data-lang-display="inline">Condition</span></label>
            <select class="customer-modal__input" name="piece_condition">
                <option value="">—</option>
                @foreach ($j['piece_conditions'] as $key => $labels)
                    <option value="{{ $key }}" data-label-ar="{{ $labels['ar'] }}" data-label-en="{{ $labels['en'] }}">{{ $labels['ar'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
