<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
   public function index(Request $request)
{
    // --- Поиск (верхнее поле "Найти")
    // поддержим и search=..., и q=... (на всякий случай)
    
	//$search = trim((string) ($request->input('search') ?? $request->input('q') ?? ''));
	
	$search = trim((string) ($request->input('search') ?? $request->input('q') ?? ''));

    // --- Текущие выбранные значения
    $selectedCategory = $request->integer('category') ?: null;
    $inStockOnly = $request->boolean('in_stock');
    $sort = $request->string('sort', 'recommended')->toString();
    if (! in_array($sort, ['recommended', 'price_asc', 'price_desc', 'newest'], true)) {
        $sort = 'recommended';
    }

    $selectedBrands = collect($request->input('brands', []))
        ->filter(fn($v) => $v !== null && $v !== '')
        ->map(fn($v) => (int)$v)
        ->values()
        ->all();

    // --- Базовый запрос ДЛЯ диапазона цен (важно: без price_min/price_max)
    $rangeQuery = Product::active()
        ->when($search !== '', function ($q) use ($search) {
            $q->where(function ($w) use ($search) {
                $w->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.description', 'like', "%{$search}%");
            });
        })
        ->when($selectedCategory, function ($q) use ($selectedCategory) {
            $q->whereHas('categories', function ($qq) use ($selectedCategory) {
                $qq->where('categories.id', $selectedCategory);
            });
        })
        ->when(!empty($selectedBrands), function ($q) use ($selectedBrands) {
            $q->whereIn('brand_id', $selectedBrands);
        });

    $priceRange = $rangeQuery
        ->selectRaw('MIN(price) as min, MAX(price) as max')
        ->first();

    $rangeMin = (int)($priceRange->min ?? 0);
    $rangeMax = (int)($priceRange->max ?? 0);

    // --- Текущие значения слайдера
    $priceMin = $request->has('price_min') ? (int)$request->input('price_min') : $rangeMin;
    $priceMax = $request->has('price_max') ? (int)$request->input('price_max') : $rangeMax;

    // Защита от кривых значений
    $priceMin = max($rangeMin, min($priceMin, $rangeMax));
    $priceMax = max($rangeMin, min($priceMax, $rangeMax));
    if ($priceMin > $priceMax) { [$priceMin, $priceMax] = [$priceMax, $priceMin]; }

    // --- Базовый запрос товаров
    $productsQuery = Product::query()
        ->with(['images', 'brand', 'categories', 'stock', 'variants.stock'])
        ->active()
        ->when($search !== '', function ($q) use ($search) {
            $q->where(function ($w) use ($search) {
                $w->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.description', 'like', "%{$search}%");
            });
        })
        ->whereBetween('price', [$priceMin, $priceMax]);

    if ($selectedCategory) {
        $productsQuery->whereHas('categories', function ($q) use ($selectedCategory) {
            $q->where('categories.id', $selectedCategory);
        });
    }

    if (!empty($selectedBrands)) {
        $productsQuery->whereIn('brand_id', $selectedBrands);
    }

    if ($inStockOnly) {
        $productsQuery->where(function ($query) {
            $query->whereHas('stock', fn ($stock) => $stock->where('quantity', '>', 0))
                ->orWhereHas('variants.stock', fn ($stock) => $stock->where('quantity', '>', 0));
        });
    }

    $products = (match ($sort) {
        'price_asc' => $productsQuery->orderBy('price'),
        'price_desc' => $productsQuery->orderByDesc('price'),
        'newest' => $productsQuery->latest(),
        default => $productsQuery->orderByDesc('is_featured')->latest(),
    })->paginate(12)
        ->appends($request->query());

    // --- Списки для фильтров
    $categories = Category::orderBy('name')->get();
    $brands     = Brand::orderBy('name')->get();

    // --- СЧЁТЧИКИ категорий
    // учитываем выбранные бренды + цену + поиск, НЕ учитываем выбранную категорию
    $categoryCounts = Category::query()
        ->select('categories.id', DB::raw('COUNT(DISTINCT products.id) as cnt'))
        ->leftJoin('category_product', 'categories.id', '=', 'category_product.category_id')
        ->leftJoin('products', 'products.id', '=', 'category_product.product_id')
        ->where('products.is_active', 1)
        ->when($search !== '', function ($q) use ($search) {
            $q->where(function ($w) use ($search) {
                $w->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.description', 'like', "%{$search}%");
            });
        })
        ->whereBetween('products.price', [$priceMin, $priceMax])
        ->when(!empty($selectedBrands), function ($q) use ($selectedBrands) {
            $q->whereIn('products.brand_id', $selectedBrands);
        })
        ->groupBy('categories.id')
        ->pluck('cnt', 'categories.id');

    // --- СЧЁТЧИКИ брендов
    // учитываем выбранную категорию + цену + поиск, НЕ учитываем выбранные бренды
    $brandCounts = Brand::query()
        ->select('brands.id', DB::raw('COUNT(DISTINCT products.id) as cnt'))
        ->leftJoin('products', 'brands.id', '=', 'products.brand_id')
        ->where('products.is_active', 1)
        ->when($search !== '', function ($q) use ($search) {
            $q->where(function ($w) use ($search) {
                $w->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.description', 'like', "%{$search}%");
            });
        })
        ->whereBetween('products.price', [$priceMin, $priceMax])
        ->when($selectedCategory, function ($q) use ($selectedCategory) {
            $q->whereExists(function ($sub) use ($selectedCategory) {
                $sub->select(DB::raw(1))
                    ->from('category_product')
                    ->whereColumn('category_product.product_id', 'products.id')
                    ->where('category_product.category_id', $selectedCategory);
            });
        })
        ->groupBy('brands.id')
        ->pluck('cnt', 'brands.id');

    // --- AJAX ответ: обновляем grid + filters
    if ($request->ajax()) {
        return response()->json([
            'filtersHtml' => view('products.partials.filters', compact(
                'categories', 'brands', 'categoryCounts', 'brandCounts',
                'rangeMin', 'rangeMax', 'priceMin', 'priceMax', 'selectedCategory', 'selectedBrands', 'search', 'inStockOnly', 'sort'
            ))->render(),
            'gridHtml' => view('products.partials.grid', compact('products'))->render(),
            'catalogMetaHtml' => view('products.partials.catalog-meta', compact(
                'products', 'categories', 'brands', 'rangeMin', 'rangeMax', 'priceMin', 'priceMax',
                'selectedCategory', 'selectedBrands', 'search', 'inStockOnly', 'sort'
            ))->render(),
            'total' => $products->total(),
        ]);
    }

    // --- Обычный рендер
    return view('products.index', compact(
        'products',
        'categories',
        'brands',
        'categoryCounts',
        'brandCounts',
        'rangeMin',
        'rangeMax',
        'priceMin',
        'priceMax',
        'selectedCategory',
        'selectedBrands',
        'search',
        'inStockOnly',
        'sort'
    ));
}
	
	

    public function show(string $identifier): View
    {
        $query = Product::with([
            'brand', 
            'categories', 
            'images',
            'stock',
            'variants' => function ($query) {
                $query->where('is_active', true)->with(['stock', 'attributeValues']);
            },
            'reviews' => function ($query) {
                $query->where('is_approved', true)->with('user');
            }
        ])->active();

        if (is_numeric($identifier)) {
            $product = $query->where('id', $identifier)->firstOrFail();
        } else {
            $product = $query->where('slug', $identifier)->firstOrFail();
        }

        $relatedProducts = Product::with(['brand', 'images', 'stock', 'variants.stock'])
            ->where('id', '!=', $product->id)
            ->active()
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

	/**
     * Поиск товаров
     */
	public function search(Request $request)
{
    $q = trim((string) $request->input('q', ''));

    // пусто — просто в каталог
    if ($q === '') {
        return redirect()->route('products.index');
    }

    // ВАЖНО: редиректим на index, чтобы все переменные (rangeMin/rangeMax/brands/счётчики/AJAX) были как надо
    return redirect()->route('products.index', [
        'search' => $q, // приводим к единому параметру
    ]);
}

    /** Lightweight suggestions for the catalogue search field. */
    public function suggestions(Request $request)
    {
        $query = trim((string) $request->input('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json(['items' => []]);
        }

        $products = Product::query()
            ->with(['images', 'brand', 'stock', 'variants.stock'])
            ->active()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%");
            })
            ->orderByDesc('is_featured')
            ->limit(6)
            ->get()
            ->map(fn (Product $product) => [
                'name' => $product->name,
                'brand' => $product->brand?->name,
                'url' => route('products.show', $product->slug),
                'image' => $product->main_image?->getUrl(),
                'price' => number_format($product->final_price, 0, ',', ' ') . ' ₽',
                'available' => $product->in_stock,
            ]);

        return response()->json(['items' => $products]);
    }
	
	
}
