<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Support\CustomerFormHelper;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::query()
            ->with('service')
            ->latest()
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer): View
    {
        $customer->load('service');

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * @return array<string, string>
     */
    public static function detailRows(Customer $customer): array
    {
        $category = $customer->item_category ?? 'jewelry';
        $details = $customer->form_details ?? [];
        $rows = [];

        if ($category === 'gemstone') {
            $map = [
                'gemstone_type' => ['gemstone_types', 'نوع الحجر'],
                'stones_count' => [null, 'عدد الأحجار'],
                'approximate_weight_carat' => [null, 'الوزن (قيراط)'],
                'shape' => ['shapes', 'الشكل'],
                'color' => [null, 'اللون'],
                'clarity_grade' => [null, 'النقاء'],
                'origin_type' => ['origin_types', 'طبيعي/مصنع'],
                'treated' => ['yes_no_unknown', 'معالجة'],
                'country_of_origin' => [null, 'بلد المنشأ'],
                'has_certificate' => ['yes_no_unknown', 'شهادة'],
                'stone_condition' => ['stone_conditions', 'الحالة'],
            ];
        } else {
            $map = [
                'piece_type' => ['piece_types', 'نوع القطعة'],
                'metal_type' => ['metal_types', 'نوع المعدن'],
                'pieces_count' => [null, 'عدد القطع'],
                'approximate_weight' => [null, 'الوزن التقريبي'],
                'karat' => [null, 'العيار'],
                'brand' => [null, 'الماركة'],
                'has_hallmark' => ['yes_no_unknown', 'دمغة/ختم'],
                'has_invoice' => ['yes_no_unknown', 'فاتورة'],
                'has_certificate' => ['yes_no_unknown', 'شهادة'],
                'piece_condition' => ['piece_conditions', 'حالة القطعة'],
            ];
        }

        foreach ($map as $key => [$group, $label]) {
            if (! filled($details[$key] ?? null)) {
                continue;
            }
            $rows[$label] = $group
                ? CustomerFormHelper::label($category, $group, (string) $details[$key])
                : (string) $details[$key];
        }

        if (! empty($details['evaluation_purpose'])) {
            $purposeGroup = $category === 'gemstone' ? 'evaluation_purposes' : 'evaluation_purposes';
            $rows['الغرض من التقييم'] = implode('، ', CustomerFormHelper::labelsList(
                $category,
                $purposeGroup,
                $details['evaluation_purpose']
            ));
        }

        if (filled($details['brief_description'] ?? null)) {
            $rows['وصف مختصر'] = (string) $details['brief_description'];
        }
        if (filled($details['additional_notes'] ?? null)) {
            $rows['ملاحظات'] = (string) $details['additional_notes'];
        }
        if (filled($details['referral_source'] ?? null)) {
            $ref = config('customer-form.referral_sources.'.$details['referral_source']);
            $rows['كيف تعرفت علينا'] = $ref['ar'] ?? $details['referral_source'];
        }

        return $rows;
    }
}
