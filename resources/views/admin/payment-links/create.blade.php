@extends('layouts.admin')

@section('title', 'رابط دفع جديد')

@section('content')
<div class="admin-card">
    <h1 class="admin-h1">رابط دفع جديد</h1>
    <p class="muted" style="margin-top:0;margin-bottom:1.25rem;">
        أدخل بيانات العميل والمبلغ؛ سيُنشأ رابط MyFatoorah يمكنك نسخه وإرساله للعميل.
    </p>

    <form method="post" action="{{ route('admin.payment-links.store') }}">
        @csrf

        @if ($errors->any())
            <div class="admin-flash admin-flash--err" style="margin:0 0 1rem;">
                <div class="admin-flash__inner">
                    @foreach ($errors->all() as $err)
                        <p style="margin:0 0 0.35rem;">{{ $err }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <style>
            .plink-grid { display: grid; gap: 1rem; }
            @media (min-width: 768px) { .plink-grid-2 { grid-template-columns: 1fr 1fr; } }
            .plink-field label { display:block; font-weight:600; margin-bottom:0.35rem; font-size:0.88rem; color: var(--muted); }
            .plink-field input, .plink-field textarea {
                width:100%; padding:0.55rem 0.65rem; border:1px solid var(--border); border-radius:8px; font-family:inherit; font-size:0.92rem;
            }
            .plink-field textarea { min-height: 80px; resize: vertical; }
            .plink-hint { font-size:0.8rem; color:var(--muted); margin-top:0.25rem; }
        </style>

        <div class="plink-grid">
            <div class="plink-grid plink-grid-2">
                <div class="plink-field">
                    <label for="customer_name">اسم العميل (اختياري)</label>
                    <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name') }}">
                </div>
                <div class="plink-field">
                    <label for="phone">رقم الجوال</label>
                    <input id="phone" type="text" name="phone" required value="{{ old('phone') }}" dir="ltr" style="text-align:right;" placeholder="05xxxxxxxx">
                    @error('phone')<p class="plink-hint" style="color:#a44;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="plink-grid plink-grid-2">
                <div class="plink-field">
                    <label for="email">البريد الإلكتروني (اختياري)</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" dir="ltr" style="text-align:right;">
                </div>
                <div class="plink-field">
                    <label for="amount">المبلغ (ر.س)</label>
                    <input id="amount" type="number" name="amount" step="0.01" min="0.01" required value="{{ old('amount') }}" dir="ltr" style="text-align:right;">
                    @error('amount')<p class="plink-hint" style="color:#a44;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="plink-field">
                <label for="description">وصف / ملاحظة (اختياري)</label>
                <textarea id="description" name="description" placeholder="مثال: دفعة مقدمة">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="admin-actions" style="margin-top:1rem;">
            <button type="submit" class="btn-admin btn-admin--primary">إنشاء رابط الدفع</button>
            <a href="{{ route('admin.payment-links.index') }}" class="btn-admin btn-admin--muted">إلغاء</a>
        </div>
    </form>
</div>
@endsection
