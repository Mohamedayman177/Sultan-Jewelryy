@extends('layouts.admin')

@section('title', 'روابط الدفع')

@section('content')
<div class="admin-card">
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:0.75rem;margin-bottom:1.25rem;">
        <div>
            <h1 class="admin-h1" style="margin-bottom:0.35rem;">روابط الدفع</h1>
            <p class="muted" style="margin:0;">إنشاء رابط دفع بمبلغ محدد وإرساله للعميل.</p>
        </div>
        <a href="{{ route('admin.payment-links.create') }}" class="btn-admin btn-admin--primary">رابط دفع جديد</a>
    </div>

    @if (session('created_payment_url'))
        <div style="margin-bottom:1.25rem;padding:0.85rem 1rem;border-radius:10px;background:rgba(46,125,50,0.08);border:1px solid rgba(46,125,50,0.35);">
            <p style="margin:0 0 0.5rem;font-weight:600;color:#1b5e20;">رابط الدفع الجديد</p>
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;align-items:center;">
                <input type="text" readonly value="{{ session('created_payment_url') }}" id="created-payment-url" dir="ltr" style="flex:1;min-width:200px;padding:0.5rem 0.65rem;border:1px solid var(--border);border-radius:8px;font-size:0.85rem;background:#fff;">
                <button type="button" class="btn-admin btn-admin--primary" id="copy-created-url">نسخ الرابط</button>
            </div>
        </div>
    @endif

    @if ($paymentLinks->isEmpty())
        <p class="muted">لا توجد روابط دفع بعد. اضغط «رابط دفع جديد» للبدء.</p>
    @else
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>الجوال</th>
                        <th>المبلغ</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>رابط الدفع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentLinks as $link)
                        <tr>
                            <td>{{ $link->id }}</td>
                            <td>{{ $link->customer_name ?: '—' }}</td>
                            <td dir="ltr" style="text-align:right;">{{ $link->phone }}</td>
                            <td>{{ number_format((float) $link->amount, 2) }} ر.س</td>
                            <td class="muted">{{ $link->description ?: '—' }}</td>
                            <td>
                                @if ($link->payment_status === 'paid')
                                    <span style="color:#1b5e20;font-weight:600;">مدفوع</span>
                                @elseif ($link->payment_status === 'pending')
                                    بانتظار الدفع
                                @elseif ($link->payment_status === 'failed')
                                    فشل الدفع
                                @else
                                    {{ $link->payment_status }}
                                @endif
                            </td>
                            <td class="muted">{{ $link->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</td>
                            <td>
                                @if ($link->invoice_url && $link->payment_status === 'pending')
                                    <button type="button" class="btn-admin btn-admin--muted copy-link-btn" data-url="{{ $link->invoice_url }}">نسخ</button>
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $paymentLinks->links() }}
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
  function copyText(text) {
    if (navigator.clipboard && window.isSecureContext) {
      return navigator.clipboard.writeText(text);
    }
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.left = '-9999px';
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    document.body.removeChild(ta);
    return Promise.resolve();
  }
  function bindCopy(btn, getUrl) {
    btn.addEventListener('click', function () {
      var url = getUrl();
      if (!url) return;
      copyText(url).then(function () {
        var prev = btn.textContent;
        btn.textContent = 'تم النسخ';
        setTimeout(function () { btn.textContent = prev; }, 2000);
      });
    });
  }
  var copyCreated = document.getElementById('copy-created-url');
  if (copyCreated) {
    bindCopy(copyCreated, function () {
      var el = document.getElementById('created-payment-url');
      return el ? el.value : '';
    });
  }
  document.querySelectorAll('.copy-link-btn').forEach(function (btn) {
    bindCopy(btn, function () { return btn.getAttribute('data-url') || ''; });
  });
})();
</script>
@endpush
