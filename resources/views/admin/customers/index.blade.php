@extends('layouts.admin')

@section('title', 'العملاء المسجّلون')

@section('content')
<div class="admin-card">
    <h1 class="admin-h1">العملاء المسجّلون</h1>
    <p class="muted" style="margin-top:0;margin-bottom:1.25rem;">
        البيانات المرسلة من نموذج الخدمات التي تتطلّب التسجيل قبل التوجيه إلى واتساب.
    </p>
    <p class="muted" style="margin-top:0;margin-bottom:1.25rem;font-size:0.88rem;line-height:1.55;">
        <strong>MyFatoorah + ngrok:</strong> أبقِ أمر <code style="font-size:0.85em;">ngrok http 127.0.0.1:8000</code> يعمل حتى تنتهي عملية الدفع؛ إذا ظهر ERR_NGROK_3200 فالتطبيق لم يستقبل الرجوع ويبقى الدفع «بانتظار الدفع».
        حدّث <code style="font-size:0.85em;">MYFATOORAH_PUBLIC_APP_URL</code> في <code style="font-size:0.85em;">.env</code> عندما يتغيّر رابط ngrok ثم <code style="font-size:0.85em;">php artisan config:clear</code>.
        لمزامنة الفواتير المدفوعة في البوابة مع النظام: <code style="font-size:0.85em;">php artisan payments:reconcile-myfatoorah</code>
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
                        <th>الدفع</th>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $customers->links() }}
    @endif
</div>
@endsection
