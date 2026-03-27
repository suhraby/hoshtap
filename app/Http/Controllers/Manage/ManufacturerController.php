<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManufacturerRequest;
use App\Http\Resources\ManufacturerResource;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ManufacturerController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $limit     = (int) $request->query('limit', 20);
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

        $manufacturers = $query
            ->orderBy($sortKey, $sortOrder)
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
            return Redirect::route('manage.manufacturers.index')->with('success', 'Manufacturer has been created.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create manufacturer. Please try again.');
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

            return Redirect::route('manage.manufacturers.index')->with('success', 'Manufacturer has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to update manufacturer. Please try again.');
        }
    }

    public function destroy(Manufacturer $manufacturer): \Illuminate\Http\RedirectResponse
    {
        $manufacturer->delete();

        return Redirect::route('manage.manufacturers.index')->with('warning', 'Manufacturer has been deleted.');
    }
}
