<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BannerController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $locale = app()->getLocale();
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

        $query = Banner::query();

        if ($search = trim((string) $request->query('searchTerm', ''))) {
            $query->where('title', 'ILIKE', "%{$search}%");
        }

        if ($sortKey === 'title') {
            $query->orderByRaw("title->>'{$locale}' {$sortOrder}");
        } else {
            $query->orderBy($sortKey, $sortOrder);
        }

        $banners = $query
            ->paginate($limit)
            ->withQueryString();

        return Inertia::render('Manage/Banners/Index', [
            'banners'    => BannerResource::collection($banners),
            'limit'      => $limit,
            'searchTerm' => $request->query('searchTerm', ''),
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Manage/Banners/Create');
    }

    public function store(BannerRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $banner = Banner::create([
                'title' => $data['title'],
            ]);

            $banner->addMediaFromRequest('file')
                ->usingName($data['title']['en'])
                ->usingFileName(Str::slug($data['title']['en']) . '.' . $request->file('file')->getClientOriginalExtension())
                ->toMediaCollection('banner_image');

            DB::commit();
            return redirect()->route('manage.banners.index')->with('success', __('Created msg', ['name' => __('Banner')]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', __('Failed to create data. Please try again.'));
        }
    }

    public function edit(Banner $banner): \Inertia\Response
    {
        return Inertia::render('Manage/Banners/Edit', [
            'banner' => new BannerResource($banner)
        ]);
    }

    public function update(BannerRequest $request, Banner $banner)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $banner->update([
                'title' => $data['title'],
            ]);

            if ($request->hasFile('file')) {
                $banner->addMediaFromRequest('file')
                    ->usingName($data['title']['en'])
                    ->usingFileName(Str::slug($data['title']['en']) . '.' . $request->file('file')->getClientOriginalExtension())
                    ->toMediaCollection('banner_image');
            }

            DB::commit();

            return redirect()->route('manage.banners.index')->with('success', __('Updated msg', ['name' => __('Banner')]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', __('Failed to update data. Please try again.'));
        }
    }

    public function destroy(Banner $banner): \Illuminate\Http\RedirectResponse
    {
        $banner->delete();

        return redirect()->route('manage.banners.index')->with('warning', __('Deleted msg', ['name' => __('Banner')]));
    }

    public function sortOrderForm(): \Inertia\Response | \Illuminate\Http\RedirectResponse
    {
        if (Banner::count() <= 1) {
            return redirect()->route('manage.banners.index')->with('warning', __('You need must be at least one :name', ['name' => __('Banner')]));
        }

        $banners = Banner::orderBy('sort_order')->get();

        return Inertia::render('Manage/Banners/Order', [
            'banners' => BannerResource::collection($banners),
        ]);
    }

    public function sortOrder(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (Banner::count() <= 1) {
            return redirect()->route('manage.banners.index')->with('warning', __('You need must be at least one :name', ['name' => __('Banner')]));
        }

        foreach ($request->input('ids', []) as $key => $id) {
            Banner::whereId($id)->update(['sort_order' => $key + 1]);
        }

        return redirect()->route('manage.banners.index')->with('success', __('Ordered msg', ['name' => __('Banner')]));
    }
}
