<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManufacturerRequest;
use App\Http\Resources\ManufacturerResource;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ManufacturerController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $locale    = app()->getLocale();
        $limit     = (int) $request->query('limit', 25);
        $sortKey   = $request->query('sortKey', 'sort_order');
        $sortOrder = $request->query('sortOrder', 'asc');

        $allowedSorts = ['title', 'sort_order'];
        if (! in_array($sortKey, $allowedSorts, true)) {
            $sortKey = 'sort_order';
        }
        if (! in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'asc';
        }

        $query = Manufacturer::query();

        if ($search = trim((string) $request->query('searchTerm', ''))) {
            $query->where('title', 'ILIKE', "%{$search}%");
        }

        if ($sortKey === 'title') {
            $query->orderByRaw("title->>'{$locale}' {$sortOrder}");
        } else {
            $query->orderBy($sortKey, $sortOrder);
        }

        $manufacturers = $query
            ->paginate($limit)
            ->withQueryString();

        return Inertia::render('Manage/Manufacturers/Index', [
            'manufacturers'    => ManufacturerResource::collection($manufacturers),
            'limit'      => $limit,
            'searchTerm' => $request->query('searchTerm', ''),
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Manage/Manufacturers/Create');
    }

    public function store(ManufacturerRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $manufacturer = Manufacturer::create([
                'title' => $data['title'],
            ]);

            $manufacturer->addMediaFromRequest('file')
                ->usingName($data['title']['en'])
                ->usingFileName(Str::slug($data['title']['en']) . '.' . $request->file('file')->getClientOriginalExtension())
                ->toMediaCollection('manufacturer_image');

            DB::commit();

            return redirect()->route('manage.manufacturers.index')->with('success', __('Created msg', ['name' => __('Manufacturer')]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', __('Failed to create data. Please try again.'));
        }
    }

    public function edit(Manufacturer $manufacturer): \Inertia\Response
    {
        return Inertia::render('Manage/Manufacturers/Edit', [
            'manufacturer' => new ManufacturerResource($manufacturer)
        ]);
    }

    public function update(ManufacturerRequest $request, Manufacturer $manufacturer)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $manufacturer->update([
                'title' => $data['title'],
            ]);

            if ($request->hasFile('file')) {
                $manufacturer->addMediaFromRequest('file')
                    ->usingName($data['title']['en'])
                    ->usingFileName(Str::slug($data['title']['en']) . '.' . $request->file('file')->getClientOriginalExtension())
                    ->toMediaCollection('manufacturer_image');
            }

            DB::commit();

            return redirect()->route('manage.manufacturers.index')->with('success', __('Updated msg', ['name' => __('Manufacturer')]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', __('Failed to update data. Please try again.'));
        }
    }

    public function destroy(Manufacturer $manufacturer): \Illuminate\Http\RedirectResponse
    {
        $manufacturer->delete();

        return redirect()->route('manage.manufacturers.index')->with('warning', __('Deleted msg', ['name' => __('Manufacturer')]));
    }

    public function sortOrderForm(): \Inertia\Response | \Illuminate\Http\RedirectResponse
    {
        if (Manufacturer::count() <= 1) {
            return redirect()->route('manage.manufacturers.index')->with('warning', __('You need must be at least one :name', ['name' => __('Manufacturer')]));
        }

        $manufacturers = Manufacturer::orderBy('sort_order')->get();

        return Inertia::render('Manage/Manufacturers/Order', [
            'manufacturers' => ManufacturerResource::collection($manufacturers),
        ]);
    }

    public function sortOrder(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (Manufacturer::count() <= 1) {
            return redirect()->route('manage.manufacturers.index')->with('warning', __('You need must be at least one :name', ['name' => __('Manufacturer')]));
        }

        foreach ($request->input('ids', []) as $key => $id) {
            Manufacturer::whereId($id)->update(['sort_order' => $key + 1]);
        }

        return redirect()->route('manage.manufacturers.index')->with('success', __('Ordered msg', ['name' => __('Manufacturer')]));
    }
}
