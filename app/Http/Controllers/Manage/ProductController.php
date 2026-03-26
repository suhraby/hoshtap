<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Support\Facades\Redirect;
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

        $query = Product::query();

        if ($search = trim((string) $request->query('searchTerm', ''))) {
            $query->where('title', 'ILIKE', "%{$search}%");
        }

        $products = $query
            ->orderBy($sortKey, $sortOrder)
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
            return Redirect::route('manage.products.index')->with('success', 'Product has been created.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create product. Please try again.');
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

            return Redirect::route('manage.products.index')->with('success', 'Product has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to update product. Please try again.');
        }
    }

    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
    {
        $product->delete();

        return Redirect::route('manage.products.index')->with('warning', 'Product has been deleted.');
    }
}
