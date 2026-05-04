@extends('layouts.admin')

@section('title', 'العملاء المسجّلون')

@section('content')
<div class="admin-card">
    <h1 class="admin-h1">العملاء المسجّلون</h1>
    <p class="muted" style="margin-top:0;margin-bottom:1.25rem;">
        البيانات المرسلة من نموذج الخدمات المدفوعة قبل التوجيه إلى واتساب.
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
                        <th>الهوية</th>
                        <th>الجوال</th>
                        <th>البريد</th>
                        <th>الخدمة</th>
                        <th>اللغة</th>
                        <th>تاريخ التسجيل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name ?: '—' }}</td>
                            <td dir="ltr" style="text-align:right;">{{ $customer->national_id ?: '—' }}</td>
                            <td dir="ltr" style="text-align:right;">{{ $customer->phone }}</td>
                            <td dir="ltr" style="text-align:right;">{{ $customer->email ?: '—' }}</td>
                            <td>
                                @php($labels = $serviceLabels[$customer->service_key] ?? null)
                                @if ($labels)
                                    {{ $labels['ar'] }}
                                    <span class="muted">/ {{ $labels['en'] }}</span>
                                @else
                                    {{ $customer->service_key }}
                                @endif
                            </td>
                            <td>{{ strtoupper($customer->locale ?? '—') }}</td>
                            <td class="muted">{{ $customer->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $customers->links() }}
    @endif
</div>
@endsection
