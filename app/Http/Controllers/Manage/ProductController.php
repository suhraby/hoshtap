<?php

namespace App\Http\Controllers\Manage;

use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
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

        $query = Product::query();

        if ($search = trim((string) $request->query('searchTerm', ''))) {
            $query->where('title', 'ILIKE', "%{$search}%");
        }

        if ($sortKey === 'title') {
            $query->orderByRaw("title->>'{$locale}' {$sortOrder}");
        } else {
            $query->orderBy($sortKey, $sortOrder);
        }

        $products = $query
            ->paginate($limit)
            ->withQueryString();

        return Inertia::render('Manage/Products/Index', [
            'products'    => ProductResource::collection($products),
            'limit'      => $limit,
            'searchTerm' => $request->query('searchTerm', ''),
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Manage/Products/Create');
    }

    public function store(ProductRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $product = Product::create([
                'title' => $data['title'],
            ]);

            $product->addMediaFromRequest('file')
                ->usingName($data['title']['en'])
                ->usingFileName(Str::slug($data['title']['en']) . '.' . $request->file('file')->getClientOriginalExtension())
                ->toMediaCollection('product_image');

            DB::commit();
            return redirect()->route('manage.products.index')->with('success', __('Created msg', ['name' => __('Product')]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', __('Failed to create data. Please try again.'));
        }
    }

    public function edit(Product $product): \Inertia\Response
    {
        return Inertia::render('Manage/Products/Edit', [
            'product' => new ProductResource($product)
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $product->update([
                'title' => $data['title'],
            ]);

            if ($request->hasFile('file')) {
                $product->addMediaFromRequest('file')
                    ->usingName($data['title']['en'])
                    ->usingFileName(Str::slug($data['title']['en']) . '.' . $request->file('file')->getClientOriginalExtension())
                    ->toMediaCollection('product_image');
            }

            DB::commit();

            return redirect()->route('manage.products.index')->with('success', __('Updated msg', ['name' => __('Product')]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', __('Failed to update data. Please try again.'));
        }
    }

    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
    {
        $product->delete();

        return redirect()->route('manage.products.index')->with('warning', __('Deleted msg', ['name' => __('Product')]));
    }

    public function sortOrderForm(): \Inertia\Response | \Illuminate\Http\RedirectResponse
    {
        if (Product::count() <= 1) {
            return redirect()->route('manage.products.index')->with('warning', __('You need must be at least one :name', ['name' => __('Product')]));
        }

        $products = Product::orderBy('sort_order')->get();

        return Inertia::render('Manage/Products/Order', [
            'products' => ProductResource::collection($products),
        ]);
    }

    public function sortOrder(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (Product::count() <= 1) {
            return redirect()->route('manage.products.index')->with('warning', __('You need must be at least one :name', ['name' => __('Product')]));
        }

        foreach ($request->input('ids', []) as $key => $id) {
            Product::whereId($id)->update(['sort_order' => $key + 1]);
        }

        return redirect()->route('manage.products.index')->with('success', __('Ordered msg', ['name' => __('Product')]));
    }
}
