<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $locale     = app()->getLocale();
        $limit      = (int) $request->query('limit', 25);
        $sortKey    = $request->query('sortKey', 'sort_order');
        $sortOrder  = $request->query('sortOrder', 'asc');
        $searchTerm = $request->query('searchTerm', '');

        $allowedSorts = ['title', 'sort_order'];
        if (! in_array($sortKey, $allowedSorts, true)) {
            $sortKey = 'sort_order';
        }

        if (! in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'asc';
        }

        $query = Service::query();

        if ($search = trim((string) $searchTerm)) {
            $query->where('title', 'ILIKE', "%{$search}%");
        }

        if ($sortKey === 'title') {
            $query->orderByRaw("title->>'{$locale}' {$sortOrder}");
        } else {
            $query->orderBy($sortKey, $sortOrder);
        }

        $services = $query
            ->paginate($limit)
            ->withQueryString();

        return Inertia::render('Manage/Services/Index', [
            'services' => ServiceResource::collection($services),
            'limit' => $limit,
            'searchTerm' => $searchTerm,
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Manage/Services/Create');
    }

    public function store(ServiceRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        Service::create([
            'icon'   => $data['icon'],
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        return redirect()->route('manage.services.index')->with('success', __('Created msg', ['name' => __('Service')]));
    }

    public function edit(Service $service): \Inertia\Response
    {
        return Inertia::render('Manage/Services/Edit', [
            'service' => new ServiceResource($service)
        ]);
    }

    public function update(ServiceRequest $request, Service $service): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        $service->update([
            'icon'   => $data['icon'],
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        return redirect()->route('manage.services.index')->with('success', __('Updated msg', ['name' => __('Service')]));
    }

    public function destroy(Service $service): \Illuminate\Http\RedirectResponse
    {
        $service->delete();

        return redirect()->route('manage.services.index')->with('warning', __('Deleted msg', ['name' => __('Service')]));
    }

    public function sortOrderForm(): \Inertia\Response | \Illuminate\Http\RedirectResponse
    {
        if (Service::count() <= 1) {
            return redirect()->route('manage.services.index')->with('warning', __('You need must be at least one :name', ['name' => __('Service')]));
        }

        $services = Service::orderBy('sort_order')->get();

        return Inertia::render('Manage/Services/Order', [
            'services' => ServiceResource::collection($services),
        ]);
    }

    public function sortOrder(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (Service::count() <= 1) {
            return redirect()->route('manage.services.index')->with('warning', __('You need must be at least one :name', ['name' => __('Service')]));
        }

        foreach ($request->input('ids', []) as $key => $id) {
            Service::whereId($id)->update(['sort_order' => $key + 1]);
        }

        return redirect()->route('manage.services.index')->with('success', __('Ordered msg', ['name' => __('Service')]));
    }
}
