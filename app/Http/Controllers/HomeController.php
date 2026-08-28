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

        $featuredPool = Product::with($withProductData)
            ->active()
            ->featured()
            ->orderByDesc('updated_at')
            ->limit(16)
            ->get();

        if ($featuredPool->isEmpty()) {
            $featuredPool = Product::with($withProductData)
                ->active()
                ->orderByDesc('updated_at')
                ->limit(16)
                ->get();
        }

        $featuredProducts = $featuredPool->shuffle()->take(8)->values();
        $heroCandidates = $featuredPool->filter(fn (Product $product): bool => $product->main_image !== null)->values();
        $heroProduct = null;

        if ($heroCandidates->isNotEmpty()) {
            $lastHeroProductId = (int) $request->session()->get('home_hero_product_id', 0);
            $availableHeroProducts = $heroCandidates->reject(
                fn (Product $product): bool => $heroCandidates->count() > 1 && $product->id === $lastHeroProductId
            );
            $heroProduct = $availableHeroProducts->random();
            $request->session()->put('home_hero_product_id', $heroProduct->id);
        }

        $featuredCategories = Category::withCount(['products' => fn ($query) => $query->active()])
            ->whereHas('products', fn ($query) => $query->active())
            ->orderByDesc('products_count')
            ->limit(6)
            ->get();

        $categories = Category::withCount(['products' => fn ($query) => $query->active()])
            ->whereHas('products', fn ($query) => $query->active())
            ->orderBy('name')
            ->get();

        $recentProducts = Product::with($withProductData)
            ->active()
            ->orderByDesc('updated_at')
            ->limit(16)
            ->get()
            ->shuffle()
            ->take(8)
            ->values();

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
            'heroProduct' => $heroProduct,
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
