<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $supplierId = $request->input('supplier_id');
        $warehouseId = $request->input('warehouse_id');
        $lowStock = $request->input('low_stock');
        $sort = $request->input('sort', 'latest');

        $products = Product::with(['category', 'supplier'])
            // Search name, SKU, or category
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('sku', 'like', '%' . $search . '%')
                      ->orWhereHas('category', function ($catQuery) use ($search) {
                          $catQuery->where('name', 'like', '%' . $search . '%');
                      });
                });
            })
            // Filter by category_id
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            // Filter by supplier_id
            ->when($supplierId, function ($query, $supplierId) {
                return $query->where('supplier_id', $supplierId);
            })
            // Filter by warehouse_id
            ->when($warehouseId, function ($query, $warehouseId) {
                return $query->whereHas('warehouseStocks', function ($q) use ($warehouseId) {
                    $q->where('warehouse_id', $warehouseId)->where('stock', '>', 0);
                });
            })
            // Filter by low stock (stock <= minimum_stock)
            ->when($lowStock === '1', function ($query) {
                return $query->whereColumn('stock', '<=', 'minimum_stock');
            })
            // Sorting
            ->when($sort, function ($query, $sort) {
                switch ($sort) {
                    case 'oldest':
                        return $query->orderBy('created_at', 'asc');
                    case 'stock_highest':
                        return $query->orderBy('stock', 'desc');
                    case 'stock_lowest':
                        return $query->orderBy('stock', 'asc');
                    case 'price_highest':
                        return $query->orderBy('price', 'desc');
                    case 'price_lowest':
                        return $query->orderBy('price', 'asc');
                    case 'latest':
                    default:
                        return $query->orderBy('created_at', 'desc');
                }
            })
            ->paginate(10)
            ->withQueryString();

        // Load categories, suppliers, and warehouses for filter selects
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('products.index', compact(
            'products',
            'search',
            'categories',
            'suppliers',
            'warehouses',
            'categoryId',
            'supplierId',
            'warehouseId',
            'lowStock',
            'sort'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
