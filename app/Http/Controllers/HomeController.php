<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function welcome(Request $request)
    {
        $searchQuery = trim((string) $request->input('q'));
        $withProductData = ['categories', 'brand', 'images', 'stock', 'variants.stock'];

        $searchResults = $searchQuery === ''
            ? collect()
            : Product::with($withProductData)->active()->search($searchQuery)->latest()->limit(12)->get();

        $featuredProducts = Product::with($withProductData)
            ->active()
            ->featured()
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::with($withProductData)->active()->orderByDesc('updated_at')->limit(8)->get();
        }

        $featuredCategories = Category::withCount(['products' => fn ($query) => $query->active()])
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->limit(6)
            ->get();

        $categories = Category::withCount(['products' => fn ($query) => $query->active()])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();

        $recentProducts = Product::with($withProductData)
            ->active()
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        $saleProducts = Product::with($withProductData)
            ->active()
            ->where(function ($query): void {
                $query->whereColumn('compare_price', '>', 'price')
                    ->orWhereHas('variants', fn ($variant) => $variant->where('is_active', true)->whereColumn('product_variants.compare_price', '>', 'product_variants.price'));
            })
            ->orderByRaw('(compare_price - price) DESC')
            ->limit(8)
            ->get();

        return view('welcome', [
            'featuredProducts' => $featuredProducts,
            'heroProduct' => $featuredProducts->first(),
            'featuredCategories' => $featuredCategories,
            'categories' => $categories,
            'productsCount' => Product::active()->count(),
            'searchResults' => $searchResults,
            'searchQuery' => $searchQuery,
            'recentProducts' => $recentProducts,
            'saleProducts' => $saleProducts,
        ]);
    }
}
