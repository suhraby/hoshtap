<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\CounterRequest;
use App\Http\Resources\CounterResource;
use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CounterController extends Controller
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

        $query = Counter::query();

        if ($search = trim((string) $searchTerm)) {
            $query->where('title', 'ILIKE', "%{$search}%");
        }

        $counters = $query
            ->orderBy($sortKey, $sortOrder)
            ->paginate($limit)
            ->withQueryString();

        return Inertia::render('Manage/Counters/Index', [
            'counters' => CounterResource::collection($counters),
            'limit' => $limit,
            'searchTerm' => $searchTerm,
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Manage/Counters/Create');
    }

    public function store(CounterRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        Counter::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'number' => $data['number'],
            'symbol' => $data['symbol'],
        ]);

        return Redirect::route('manage.counters.index')->with('success', 'Counter has been created.');
    }

    public function edit(Counter $counter): \Inertia\Response
    {
        return Inertia::render('Manage/Counters/Edit', [
            'counter' => new CounterResource($counter)
        ]);
    }

    public function update(CounterRequest $request, Counter $counter): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        $counter->update([
            'title'         => $data['title'],
            'description'   => $data['description'],
            'number'        => $data['number'],
            'symbol'        => $data['symbol'],
        ]);

        return Redirect::route('manage.counters.index')->with('success', 'Counter has been updated.');
    }

    public function destroy(Counter $counter): \Illuminate\Http\RedirectResponse
    {
        $counter->delete();

        return Redirect::route('manage.counters.index')->with('warning', 'Counter has been deleted.');
    }
}
