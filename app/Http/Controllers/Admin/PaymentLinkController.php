<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentLink;
use App\Services\MyFatoorahInvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentLinkController extends Controller
{
    public function index(): View
    {
        $paymentLinks = PaymentLink::query()
            ->latest()
            ->paginate(20);

        return view('admin.payment-links.index', compact('paymentLinks'));
    }

    public function create(): View
    {
        return view('admin.payment-links.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! filled(config('services.myfatoorah.api_key'))) {
            return redirect()
                ->route('admin.payment-links.create')
                ->with('flash_error', 'بوابة الدفع غير مهيأة. أضف MYFATOORAH_API_KEY في ملف البيئة.');
        }

        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'phone.required' => 'رقم الجوال مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'amount.required' => 'المبلغ مطلوب.',
            'amount.min' => 'يجب أن يكون المبلغ أكبر من صفر.',
        ]);

        $paymentLink = PaymentLink::create([
            'customer_name' => filled($validated['customer_name'] ?? null)
                ? trim((string) $validated['customer_name'])
                : null,
            'phone' => trim((string) $validated['phone']),
            'email' => filled($validated['email'] ?? null)
                ? trim((string) $validated['email'])
                : null,
            'amount' => $validated['amount'],
            'description' => filled($validated['description'] ?? null)
                ? trim((string) $validated['description'])
                : null,
            'payment_status' => 'pending',
            'created_by' => $request->user()?->id,
        ]);

        $invoiceService = MyFatoorahInvoiceService::fromConfig();

        $result = $invoiceService->createInvoiceLink([
            'amount' => (float) $paymentLink->amount,
            'customer_name' => $paymentLink->customer_name ?? 'عميل',
            'phone' => $paymentLink->phone,
            'email' => $paymentLink->email,
            'customer_reference' => $paymentLink->myfatoorahCustomerReference(),
        ]);

        if (! $result['success']) {
            Log::warning('MyFatoorah SendPayment failed (admin payment link)', [
                'payment_link_id' => $paymentLink->id,
                'detail' => $result['error'],
            ]);

            $paymentLink->delete();

            return redirect()
                ->route('admin.payment-links.create')
                ->withInput()
                ->with('flash_error', 'تعذّر إنشاء رابط الدفع من البوابة.'.($result['error'] ? ' '.$result['error'] : ''));
        }

        $paymentLink->update([
            'myfatoorah_invoice_id' => $result['invoice_id'],
            'invoice_url' => $result['invoice_url'],
        ]);

        return redirect()
            ->route('admin.payment-links.index')
            ->with('flash_ok', 'تم إنشاء رابط الدفع بنجاح. انسخه وأرسله للعميل.')
            ->with('created_payment_url', $result['invoice_url']);
    }
}
