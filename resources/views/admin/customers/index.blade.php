@extends('layouts.admin')

@section('title', 'العملاء المسجّلون')

@section('content')
<div class="admin-card">
    <h1 class="admin-h1">العملاء المسجّلون</h1>
    <p class="muted" style="margin-top:0;margin-bottom:1.25rem;">
        البيانات المرسلة من نموذج الخدمات التي تتطلّب التسجيل قبل التوجيه إلى واتساب.
    </p>

    @if ($customers->isEmpty())
        <p class="muted">لا يوجد عملاء مسجّلون بعد.</p>
    @else
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>نوع القطعة</th>
                        <th>الجوال</th>
                        <th>المدينة</th>
                        <th>الخدمة</th>
                        <th>الدفع</th>
                        <th>اللغة</th>
                        <th>تاريخ التسجيل</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name ?: '—' }}</td>
                            <td>{{ $customer->item_category ? $customer->itemCategoryLabel() : '—' }}</td>
                            <td dir="ltr" style="text-align:right;">{{ $customer->phone }}</td>
                            <td>{{ $customer->city ?: '—' }}</td>
                            <td>
                                @if ($customer->service)
                                    {{ $customer->service->title_ar }}
                                    <span class="muted">/ {{ $customer->service->title_en }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="muted">
                                @if ($customer->payment_status === 'paid')
                                    مدفوع
                                @elseif ($customer->payment_status === 'pending')
                                    بانتظار الدفع
                                @elseif ($customer->payment_status === 'failed')
                                    فشل الدفع
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ strtoupper($customer->locale ?? '—') }}</td>
                            <td class="muted">{{ $customer->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</td>
                            <td><a href="{{ route('admin.customers.show', $customer) }}" class="btn-admin btn-admin--muted">تفاصيل</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $customers->links() }}
    @endif
</div>
@endsection
