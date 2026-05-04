@extends('layouts.admin')

@section('title', 'الخدمات')

@section('content')
<div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
        <h1 class="admin-h1" style="margin:0;">الخدمات المعروضة في الموقع</h1>
        <a href="{{ route('admin.services.create') }}" class="btn-admin btn-admin--primary">إضافة خدمة</a>
    </div>
    <p class="muted" style="margin-top:0;">
        ترتيب العرض حسب «ترتيب العرض». الخدمات المعطّلة لا تظهر في الصفحة الرئيسية.
    </p>

    @if ($services->isEmpty())
        <p class="muted">لا توجد خدمات بعد.</p>
    @else
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ترتيب</th>
                        <th>العنوان (عربي)</th>
                        <th>Slug</th>
                        <th>السعر</th>
                        <th>تسجيل قبل واتساب</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td>{{ $service->sort_order }}</td>
                            <td>{{ $service->title_ar }}</td>
                            <td class="muted" dir="ltr" style="text-align:right;">{{ $service->slug }}</td>
                            <td>
                                @if ($service->is_free)
                                    <span class="muted">مجاني</span>
                                @else
                                    {{ number_format((float) $service->price, 0) }} SAR
                                @endif
                            </td>
                            <td>{{ $service->requires_registration ? 'نعم' : 'لا (واتساب مباشر)' }}</td>
                            <td>{{ $service->is_active ? 'نشط' : 'معطّل' }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn-admin btn-admin--muted">تعديل</a>
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="post" onsubmit="return confirm('حذف هذه الخدمة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-admin btn-admin--danger">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $services->links() }}
    @endif
</div>
@endsection
