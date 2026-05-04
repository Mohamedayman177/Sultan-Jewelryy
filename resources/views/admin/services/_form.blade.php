@php
    $isFree = old('is_free', $service->is_free ?? false);
@endphp

@if ($errors->any())
    <div class="admin-flash admin-flash--err" style="margin:0 0 1rem;">
        <div class="admin-flash__inner">
            @foreach ($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    </div>
@endif

<style>
    .svc-grid { display: grid; gap: 1rem; }
    @media (min-width: 768px) {
        .svc-grid-2 { grid-template-columns: 1fr 1fr; }
    }
    .svc-field label { display:block; font-weight:600; margin-bottom:0.35rem; font-size:0.88rem; color: var(--muted); }
    .svc-field input[type=text], .svc-field input[type=number], .svc-field textarea {
        width:100%; padding:0.55rem 0.65rem; border:1px solid var(--border); border-radius:8px; font-family:inherit; font-size:0.92rem;
    }
    .svc-field textarea { min-height: 100px; resize: vertical; }
    .svc-check { display:flex; align-items:center; gap:0.5rem; font-size:0.9rem; flex-wrap:wrap; }
    .svc-hint { font-size:0.8rem; color:var(--muted); margin-top:0.25rem; }
</style>

<div class="svc-grid">
    <div class="svc-field svc-grid-2 svc-grid" style="grid-column: 1 / -1;">
        <div>
            <label for="slug">Slug (اختياري — يُنشأ من العنوان الإنجليزي إذا تُرك فارغاً)</label>
            <input id="slug" type="text" name="slug" value="{{ old('slug', $service->slug) }}" dir="ltr" style="text-align:right;" placeholder="مثال: instant_consultation">
            @error('slug')<div class="svc-hint" style="color:#a44;">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="sort_order">ترتيب العرض</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="65535" required value="{{ old('sort_order', $service->sort_order ?? 0) }}">
        </div>
    </div>

    <div class="svc-grid svc-grid-2" style="grid-column: 1 / -1;">
        <div class="svc-field">
            <label for="title_ar">العنوان (عربي)</label>
            <input id="title_ar" type="text" name="title_ar" required value="{{ old('title_ar', $service->title_ar) }}">
        </div>
        <div class="svc-field">
            <label for="title_en">العنوان (إنجليزي)</label>
            <input id="title_en" type="text" name="title_en" required value="{{ old('title_en', $service->title_en) }}" dir="ltr" style="text-align:right;">
        </div>
    </div>

    <div class="svc-field" style="grid-column: 1 / -1;">
        <label for="description_ar">الوصف (عربي)</label>
        <textarea id="description_ar" name="description_ar" required>{{ old('description_ar', $service->description_ar) }}</textarea>
    </div>
    <div class="svc-field" style="grid-column: 1 / -1;">
        <label for="description_en">الوصف (إنجليزي)</label>
        <textarea id="description_en" name="description_en" required dir="ltr">{{ old('description_en', $service->description_en) }}</textarea>
    </div>

    <div class="svc-field svc-grid-2 svc-grid" style="grid-column: 1 / -1;">
        <div class="svc-check">
            <input type="checkbox" name="is_free" value="1" id="is_free" @checked(old('is_free', $service->is_free ?? false))>
            <label for="is_free" style="margin:0;">خدمة مجانية</label>
        </div>
        <div class="svc-check">
            <input type="checkbox" name="requires_registration" value="1" id="requires_registration" @checked(old('requires_registration', $service->requires_registration ?? true))>
            <label for="requires_registration" style="margin:0;">يطلب نموذج التسجيل قبل واتساب</label>
        </div>
        <div class="svc-check">
            <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $service->is_active ?? true))>
            <label for="is_active" style="margin:0;">نشط (يظهر في الموقع)</label>
        </div>
    </div>

    <div class="svc-field" style="grid-column: 1 / -1;">
        <label for="price_field">السعر (رقم — يُتجاهل عند «مجانية»)</label>
        <input id="price_field" type="number" name="price" step="0.01" min="0"
            value="{{ old('price', $service->exists && ! $service->is_free ? $service->price : '') }}"
            @disabled($isFree)>
        <div class="svc-hint">عند إلغاء «مجانية» أدخل السعر بالريال (سيُعرض مع SAR في الموقع).</div>
    </div>
</div>

@push('scripts')
<script>
(function () {
  const freeCb = document.getElementById('is_free');
  const price = document.getElementById('price_field');
  if (!freeCb || !price) return;
  function sync() {
    const on = freeCb.checked;
    price.disabled = on;
    if (on) price.value = '';
  }
  freeCb.addEventListener('change', sync);
  sync();
  price.closest('form')?.addEventListener('submit', function () {
    price.disabled = false;
  });
})();
</script>
@endpush
