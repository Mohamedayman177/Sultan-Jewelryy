<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\Service;
use App\Support\CustomerFormHelper;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $data = $request->validated();
        $locale = ($data['locale'] ?? 'ar') === 'en' ? 'en' : 'ar';

        Service::query()
            ->where('is_active', true)
            ->where('requires_registration', true)
            ->findOrFail((int) $data['service_id']);

        $itemCategory = (string) $data['item_category'];
        $formDetails = $itemCategory === 'gemstone'
            ? CustomerFormHelper::gemstoneDetailsFromRequest($data)
            : CustomerFormHelper::jewelryDetailsFromRequest($data);

        $customer = Customer::create([
            'name' => trim((string) $data['name']),
            'phone' => trim((string) $data['phone']),
            'email' => filled($data['email'] ?? null) ? trim((string) $data['email']) : null,
            'city' => trim((string) $data['city']),
            'service_id' => (int) $data['service_id'],
            'item_category' => $itemCategory,
            'form_details' => $formDetails,
            'locale' => $locale,
            'terms_accepted_at' => now(),
            'national_id' => null,
            'payment_status' => null,
        ]);
        $this->storeUploadedFiles($request, $customer);

        return response()->json([
            'whatsapp_url' => $customer->fresh(['service'])->whatsappContactUrl(),
        ]);
    }

    private function storeUploadedFiles(StoreCustomerRequest $request, Customer $customer): void
    {
        $stored = [];
        $base = 'customer-submissions/'.$customer->id;

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $path = $file->store($base.'/photos', 'public');
                $stored[] = [
                    'type' => 'photo',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'label' => 'صورة '.($index + 1),
                ];
            }
        }

        if ($request->hasFile('invoice')) {
            $file = $request->file('invoice');
            $path = $file->store($base.'/invoice', 'public');
            $stored[] = [
                'type' => 'invoice',
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'label' => 'فاتورة',
            ];
        }

        if ($request->hasFile('certificates')) {
            foreach ($request->file('certificates') as $index => $file) {
                $path = $file->store($base.'/certificates', 'public');
                $stored[] = [
                    'type' => 'certificate',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'label' => 'شهادة '.($index + 1),
                ];
            }
        }

        if ($stored !== []) {
            $customer->update(['attachments' => $stored]);
        }
    }
}
