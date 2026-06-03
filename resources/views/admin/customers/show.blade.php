@extends('layouts.admin')

@section('title', 'تفاصيل العميل #'.$customer->id)

@section('content')
@php
    $detailRows = \App\Http\Controllers\Admin\CustomerController::detailRows($customer);
@endphp
<div class="admin-card">
    <div class="admin-actions" style="margin-bottom:1.25rem;">
        <a href="{{ route('admin.customers.index') }}" class="btn-admin btn-admin--muted">← العودة للقائمة</a>
    </div>

    <h1 class="admin-h1">طلب #{{ $customer->id }}</h1>
    <p class="muted" style="margin-top:0;">{{ $customer->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</p>

    <div style="display:grid;gap:1.25rem;margin-top:1.25rem;">
        <section>
            <h2 style="font-size:1rem;margin:0 0 0.75rem;">البيانات الأساسية</h2>
            <table class="admin-table">
                <tbody>
                    <tr><th style="width:180px;">الاسم</th><td>{{ $customer->name ?: '—' }}</td></tr>
                    <tr><th>الجوال</th><td dir="ltr">{{ $customer->phone }}</td></tr>
                    <tr><th>البريد</th><td dir="ltr">{{ $customer->email ?: '—' }}</td></tr>
                    <tr><th>المدينة</th><td>{{ $customer->city ?: '—' }}</td></tr>
                    <tr><th>نوع القطعة</th><td>{{ $customer->itemCategoryLabel() }}</td></tr>
                    <tr><th>الخدمة</th><td>
                        @if ($customer->service)
                            {{ $customer->service->title_ar }} / {{ $customer->service->title_en }}
                        @else — @endif
                    </td></tr>
                    <tr><th>الدفع</th><td>
                        @if ($customer->payment_status === 'paid') مدفوع
                        @elseif ($customer->payment_status === 'pending') بانتظار الدفع
                        @elseif ($customer->payment_status === 'failed') فشل
                        @else — @endif
                    </td></tr>
                </tbody>
            </table>
        </section>

        @if ($detailRows !== [])
            <section>
                <h2 style="font-size:1rem;margin:0 0 0.75rem;">تفاصيل التقييم</h2>
                <table class="admin-table">
                    <tbody>
                        @foreach ($detailRows as $label => $value)
                            <tr><th>{{ $label }}</th><td style="white-space:pre-wrap;">{{ $value }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endif

        @php $files = $customer->attachmentLinks(); @endphp
        @if ($files !== [])
            <section>
                <h2 style="font-size:1rem;margin:0 0 0.75rem;">المرفقات</h2>
                <ul style="margin:0;padding:0;list-style:none;">
                    @foreach ($files as $file)
                        <li style="margin-bottom:0.35rem;">
                            <a href="{{ $file['url'] }}" target="_blank" rel="noopener">{{ $file['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
</div>
@endsection
