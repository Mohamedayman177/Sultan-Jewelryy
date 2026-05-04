<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()->ordered()->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create', ['service' => new Service([
            'sort_order' => (int) (Service::query()->max('sort_order') ?? 0) + 10,
            'is_active' => true,
            'is_free' => false,
            'requires_registration' => true,
        ])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Service::create($this->validated($request));

        return redirect()->route('admin.services.index')->with('flash_ok', 'تم إنشاء الخدمة بنجاح.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->validated($request, $service));

        return redirect()->route('admin.services.index')->with('flash_ok', 'تم تحديث الخدمة.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->customers()->exists()) {
            return redirect()->route('admin.services.index')->with('flash_error', 'لا يمكن الحذف: توجد طلبات عملاء مرتبطة بهذه الخدمة.');
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('flash_ok', 'تم حذف الخدمة.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Service $existing = null): array
    {
        $flags = [
            'is_free' => $request->boolean('is_free'),
            'requires_registration' => $request->boolean('requires_registration'),
            'is_active' => $request->boolean('is_active'),
        ];

        $validated = validator(
            array_merge($request->all(), $flags),
            [
                'title_ar' => ['required', 'string', 'max:255'],
                'title_en' => ['required', 'string', 'max:255'],
                'description_ar' => ['required', 'string'],
                'description_en' => ['required', 'string'],
                'is_free' => ['sometimes', 'boolean'],
                'requires_registration' => ['sometimes', 'boolean'],
                'price' => [
                    Rule::requiredIf(! $flags['is_free']),
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
                'is_active' => ['sometimes', 'boolean'],
                'slug' => ['nullable', 'string', 'max:128'],
            ]
        )->validate();

        $slug = filled($request->input('slug'))
            ? Str::slug(trim((string) $request->input('slug')))
            : Str::slug((string) $validated['title_en']);

        $slugTaken = Service::query()
            ->where('slug', $slug)
            ->when($existing, fn ($q) => $q->where('id', '!=', $existing->id))
            ->exists();

        if ($slugTaken) {
            throw ValidationException::withMessages([
                'slug' => 'المعرّف (slug) مستخدم بالفعل. غيّر العنوان الإنجليزي أو أدخل slug يدوياً.',
            ]);
        }

        if ($flags['is_free']) {
            $validated['price'] = null;
        } else {
            $validated['price'] = $validated['price'] ?? 0;
        }

        $validated['slug'] = $slug;
        $validated['is_free'] = $flags['is_free'];
        $validated['requires_registration'] = $flags['requires_registration'];
        $validated['is_active'] = $flags['is_active'];

        return $validated;
    }
}
