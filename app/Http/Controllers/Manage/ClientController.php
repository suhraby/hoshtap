<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ClientController extends Controller
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

        $query = Client::query();

        if ($search = trim((string) $request->query('searchTerm', ''))) {
            $query->where('title', 'ILIKE', "%{$search}%");
        }

        $clients = $query
            ->orderBy($sortKey, $sortOrder)
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
            return Redirect::route('manage.clients.index')->with('success', 'Client has been created.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create client. Please try again.');
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

            return Redirect::route('manage.clients.index')->with('success', 'Client has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to update client. Please try again.');
        }
    }

    public function destroy(Client $client): \Illuminate\Http\RedirectResponse
    {
        $client->delete();

        return Redirect::route('manage.clients.index')->with('warning', 'Client has been deleted.');
    }
}
