<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $limit      = $request->query('limit', 20);
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

        $services = $query
            ->orderBy($sortKey, $sortOrder)
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

        return Redirect::route('manage.services.index')->with('success', 'Service has been created.');
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

        return Redirect::route('manage.services.index')->with('success', 'Service has been updated.');
    }

    public function destroy(Service $service): \Illuminate\Http\RedirectResponse
    {
        $service->delete();

        return Redirect::route('manage.services.index')->with('warning', 'Service has been deleted.');
    }
}
