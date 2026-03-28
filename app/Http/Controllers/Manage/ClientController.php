<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ClientController extends Controller
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

        $query = Client::query();

        if ($search = trim((string) $request->query('searchTerm', ''))) {
            $query->where('title', 'ILIKE', "%{$search}%");
        }

        if ($sortKey === 'title') {
            $query->orderByRaw("title->>'{$locale}' {$sortOrder}");
        } else {
            $query->orderBy($sortKey, $sortOrder);
        }

        $clients = $query
            ->paginate($limit)
            ->withQueryString();

        return Inertia::render('Manage/Clients/Index', [
            'clients'    => ClientResource::collection($clients),
            'limit'      => $limit,
            'searchTerm' => $request->query('searchTerm', ''),
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Manage/Clients/Create');
    }

    public function store(ClientRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $client = Client::create([
                'title' => $data['title'],
            ]);

            $client->addMediaFromRequest('file')
                ->usingName($data['title']['en'])
                ->usingFileName(Str::slug($data['title']['en']) . '.' . $request->file('file')->getClientOriginalExtension())
                ->toMediaCollection('client_image');

            DB::commit();
            return redirect()->route('manage.clients.index')->with('success', __('Created msg', ['name' => __('Client')]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', __('Failed to create data. Please try again.'));
        }
    }

    public function edit(Client $client): \Inertia\Response
    {
        return Inertia::render('Manage/Clients/Edit', [
            'client' => new ClientResource($client)
        ]);
    }

    public function update(ClientRequest $request, Client $client)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $client->update([
                'title' => $data['title'],
            ]);

            if ($request->hasFile('file')) {
                $client->addMediaFromRequest('file')
                    ->usingName($data['title']['en'])
                    ->usingFileName(Str::slug($data['title']['en']) . '.' . $request->file('file')->getClientOriginalExtension())
                    ->toMediaCollection('client_image');
            }

            DB::commit();

            return redirect()->route('manage.clients.index')->with('success', __('Updated msg', ['name' => __('Client')]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', __('Failed to update data. Please try again.'));
        }
    }

    public function destroy(Client $client): \Illuminate\Http\RedirectResponse
    {
        $client->delete();

        return redirect()->route('manage.clients.index')->with('warning', __('Deleted msg', ['name' => __('Client')]));
    }

    public function sortOrderForm(): \Inertia\Response | \Illuminate\Http\RedirectResponse
    {
        if (Client::count() <= 1) {
            return redirect()->route('manage.clients.index')->with('warning', __('You need must be at least one :name', ['name' => __('Client')]));
        }

        $clients = Client::orderBy('sort_order')->get();

        return Inertia::render('Manage/Clients/Order', [
            'clients' => ClientResource::collection($clients),
        ]);
    }

    public function sortOrder(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (Client::count() <= 1) {
            return redirect()->route('manage.clients.index')->with('warning', __('You need must be at least one :name', ['name' => __('Client')]));
        }

        foreach ($request->input('ids', []) as $key => $id) {
            Client::whereId($id)->update(['sort_order' => $key + 1]);
        }

        return redirect()->route('manage.clients.index')->with('success', __('Ordered msg', ['name' => __('Client')]));
    }
}
