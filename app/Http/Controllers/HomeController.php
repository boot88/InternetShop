<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function welcome(Request $request)
    {
        $searchQuery = trim((string) $request->input('q'));
        $withProductData = ['categories', 'brand', 'images', 'stock', 'variants.stock'];

        $searchResults = $searchQuery === ''
            ? collect()
            : Product::with($withProductData)
                ->active()
                ->search($searchQuery)
                ->latest()
                ->limit(12)
                ->get();

        $featuredProducts = Product::with($withProductData)
            ->active()
            ->featured()
            ->latest()
            ->limit(8)
            ->get();

        $featuredCategories = Category::withCount(['products' => fn ($query) => $query->active()])
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->limit(6)
            ->get();

        $categories = Category::withCount(['products' => fn ($query) => $query->active()])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();

        $popularProducts = Product::query()
            ->with($withProductData)
            ->active()
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->select('products.*', DB::raw('COALESCE(SUM(order_items.quantity), 0) as sold_qty'))
            ->groupBy('products.id')
            ->orderByDesc('sold_qty')
            ->latest('products.created_at')
            ->limit(8)
            ->get();

        $newProducts = Product::with($withProductData)
            ->active()
            ->latest()
            ->limit(8)
            ->get();

        $saleProducts = Product::with($withProductData)
            ->active()
            ->whereColumn('compare_price', '>', 'price')
            ->orderByRaw('(compare_price - price) DESC')
            ->limit(8)
            ->get();

        return view('welcome', compact(
            'featuredProducts',
            'featuredCategories',
            'categories',
            'searchResults',
            'searchQuery',
            'popularProducts',
            'newProducts',
            'saleProducts',
        ));
    }
}
