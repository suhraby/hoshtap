<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\CounterRequest;
use App\Http\Resources\CounterResource;
use App\Models\Counter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CounterController extends Controller
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

        $query = Counter::query();

        if ($search = trim((string) $searchTerm)) {
            $query->where('title', 'ILIKE', "%{$search}%");
        }

        if ($sortKey === 'title') {
            $query->orderByRaw("title->>'{$locale}' {$sortOrder}");
        } else {
            $query->orderBy($sortKey, $sortOrder);
        }

        $counters = $query
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

        return redirect()->route('manage.counters.index')->with('success', __('Created msg', ['name' => __('Counter')]));
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

        return redirect()->route('manage.counters.index')->with('success', __('Updated msg', ['name' => __('Counter')]));
    }

    public function destroy(Counter $counter): \Illuminate\Http\RedirectResponse
    {
        $counter->delete();

        return redirect()->route('manage.counters.index')->with('warning', __('Deleted msg', ['name' => __('Counter')]));
    }

    public function sortOrderForm(): \Inertia\Response | \Illuminate\Http\RedirectResponse
    {
        if (Counter::count() <= 1) {
            return redirect()->route('manage.counters.index')->with('warning', __('You need must be at least one :name', ['name' => __('Counter')]));
        }

        $counters = Counter::orderBy('sort_order')->get();

        return Inertia::render('Manage/Counters/Order', [
            'counters' => CounterResource::collection($counters),
        ]);
    }

    public function sortOrder(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (Counter::count() <= 1) {
            return redirect()->route('manage.counters.index')->with('warning', __('You need must be at least one :name', ['name' => __('Counter')]));
        }

        foreach ($request->input('ids', []) as $key => $id) {
            Counter::whereId($id)->update(['sort_order' => $key + 1]);
        }

        return redirect()->route('manage.counters.index')->with('success', __('Ordered msg', ['name' => __('Counter')]));
    }
}
