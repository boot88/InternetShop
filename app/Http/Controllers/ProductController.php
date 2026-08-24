<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) ($request->input('search') ?? $request->input('q') ?? ''));
        $selectedCategory = $request->integer('category') ?: null;
        $inStockOnly = $request->boolean('in_stock');
        $sort = $request->string('sort', 'recommended')->toString();
        if (! in_array($sort, ['recommended', 'price_asc', 'price_desc', 'newest'], true)) {
            $sort = 'recommended';
        }

        $selectedBrands = collect($request->input('brands', []))
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values()
            ->all();

        $effectivePrice = "CASE WHEN products.has_variants = 1 THEN COALESCE((SELECT MIN(pv.price) FROM product_variants pv WHERE pv.product_id = products.id AND pv.is_active = 1), products.price) ELSE products.price END";
        $applySearch = function ($query) use ($search): void {
            $query->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested->where('products.name', 'like', "%{$search}%")
                        ->orWhere('products.description', 'like', "%{$search}%")
                        ->orWhere('products.sku', 'like', "%{$search}%")
                        ->orWhereHas('brand', fn ($brand) => $brand->where('name', 'like', "%{$search}%"));
                });
            });
        };
        $applyStock = function ($query) use ($inStockOnly): void {
            $query->when($inStockOnly, function ($query): void {
                $query->where(function ($nested): void {
                    $nested->whereHas('stock', fn ($stock) => $stock->where('quantity', '>', 0))
                        ->orWhereHas('variants.stock', fn ($stock) => $stock->where('quantity', '>', 0));
                });
            });
        };

        $rangeQuery = Product::query()->active();
        $applySearch($rangeQuery);
        $rangeQuery
            ->when($selectedCategory, fn ($query) => $query->whereHas('categories', fn ($category) => $category->where('categories.id', $selectedCategory)))
            ->when($selectedBrands, fn ($query) => $query->whereIn('brand_id', $selectedBrands));
        $applyStock($rangeQuery);
        $priceRange = $rangeQuery->selectRaw("MIN({$effectivePrice}) as min, MAX({$effectivePrice}) as max")->first();
        $rangeMin = (int) floor((float) ($priceRange->min ?? 0));
        $rangeMax = (int) ceil((float) ($priceRange->max ?? 0));

        $priceMin = $request->has('price_min') ? (int) $request->input('price_min') : $rangeMin;
        $priceMax = $request->has('price_max') ? (int) $request->input('price_max') : $rangeMax;
        if ($rangeMax > 0) {
            $priceMin = max($rangeMin, min($priceMin, $rangeMax));
            $priceMax = max($rangeMin, min($priceMax, $rangeMax));
        }
        if ($priceMin > $priceMax) {
            [$priceMin, $priceMax] = [$priceMax, $priceMin];
        }

        $productsQuery = Product::query()
            ->with(['images', 'brand', 'categories', 'stock', 'variants.stock'])
            ->active()
            ->select('products.*')
            ->selectRaw("{$effectivePrice} as effective_price");
        $applySearch($productsQuery);
        $applyStock($productsQuery);
        $productsQuery
            ->whereRaw("{$effectivePrice} BETWEEN ? AND ?", [$priceMin, $priceMax])
            ->when($selectedCategory, fn ($query) => $query->whereHas('categories', fn ($category) => $category->where('categories.id', $selectedCategory)))
            ->when($selectedBrands, fn ($query) => $query->whereIn('brand_id', $selectedBrands));

        $products = (match ($sort) {
            'price_asc' => $productsQuery->orderBy('effective_price'),
            'price_desc' => $productsQuery->orderByDesc('effective_price'),
            'newest' => $productsQuery->latest('products.created_at'),
            default => $productsQuery->orderByDesc('is_featured')->latest('products.updated_at'),
        })->paginate(12)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $quickCategories = Category::withCount(['products' => fn ($query) => $query->active()])
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->limit(6)
            ->get();

        $categoryCountsQuery = Category::query()
            ->select('categories.id', DB::raw('COUNT(DISTINCT products.id) as cnt'))
            ->leftJoin('category_product', 'categories.id', '=', 'category_product.category_id')
            ->leftJoin('products', 'products.id', '=', 'category_product.product_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->where('products.is_active', true)
            ->when($search !== '', fn ($query) => $query->where(fn ($nested) => $nested
                ->where('products.name', 'like', "%{$search}%")
                ->orWhere('products.description', 'like', "%{$search}%")
                ->orWhere('products.sku', 'like', "%{$search}%")
                ->orWhere('brands.name', 'like', "%{$search}%")))
            ->whereRaw("{$effectivePrice} BETWEEN ? AND ?", [$priceMin, $priceMax])
            ->when($selectedBrands, fn ($query) => $query->whereIn('products.brand_id', $selectedBrands));
        if ($inStockOnly) {
            $categoryCountsQuery->where(function ($query): void {
                $query->whereExists(fn ($stock) => $stock->selectRaw('1')->from('stocks')->whereColumn('stocks.product_id', 'products.id')->whereNull('stocks.variant_id')->where('stocks.quantity', '>', 0))
                    ->orWhereExists(fn ($variant) => $variant->selectRaw('1')->from('product_variants')->join('stocks', 'stocks.variant_id', '=', 'product_variants.id')->whereColumn('product_variants.product_id', 'products.id')->where('product_variants.is_active', true)->where('stocks.quantity', '>', 0));
            });
        }
        $categoryCounts = $categoryCountsQuery->groupBy('categories.id')->pluck('cnt', 'categories.id');

        $brandCountsQuery = Brand::query()
            ->select('brands.id', DB::raw('COUNT(DISTINCT products.id) as cnt'))
            ->leftJoin('products', 'brands.id', '=', 'products.brand_id')
            ->where('products.is_active', true)
            ->when($search !== '', fn ($query) => $query->where(fn ($nested) => $nested
                ->where('products.name', 'like', "%{$search}%")
                ->orWhere('products.description', 'like', "%{$search}%")
                ->orWhere('products.sku', 'like', "%{$search}%")
                ->orWhere('brands.name', 'like', "%{$search}%")))
            ->whereRaw("{$effectivePrice} BETWEEN ? AND ?", [$priceMin, $priceMax])
            ->when($selectedCategory, fn ($query) => $query->whereExists(fn ($sub) => $sub->selectRaw('1')->from('category_product')->whereColumn('category_product.product_id', 'products.id')->where('category_product.category_id', $selectedCategory)));
        if ($inStockOnly) {
            $brandCountsQuery->where(function ($query): void {
                $query->whereExists(fn ($stock) => $stock->selectRaw('1')->from('stocks')->whereColumn('stocks.product_id', 'products.id')->whereNull('stocks.variant_id')->where('stocks.quantity', '>', 0))
                    ->orWhereExists(fn ($variant) => $variant->selectRaw('1')->from('product_variants')->join('stocks', 'stocks.variant_id', '=', 'product_variants.id')->whereColumn('product_variants.product_id', 'products.id')->where('product_variants.is_active', true)->where('stocks.quantity', '>', 0));
            });
        }
        $brandCounts = $brandCountsQuery->groupBy('brands.id')->pluck('cnt', 'brands.id');

        $viewData = compact(
            'products', 'categories', 'brands', 'quickCategories', 'categoryCounts', 'brandCounts',
            'rangeMin', 'rangeMax', 'priceMin', 'priceMax', 'selectedCategory', 'selectedBrands',
            'search', 'inStockOnly', 'sort'
        );

        if ($request->ajax()) {
            return response()->json([
                'filtersHtml' => view('products.partials.filters', $viewData)->render(),
                'gridHtml' => view('products.partials.grid', $viewData)->render(),
                'catalogMetaHtml' => view('products.partials.catalog-meta', $viewData)->render(),
                'quickCategoriesHtml' => view('products.partials.quick-categories', $viewData)->render(),
                'total' => $products->total(),
            ]);
        }

        return view('products.index', $viewData);
    }

    public function show(Request $request, string $slug): View
    {
        $product = Product::with([
            'brand', 'categories', 'images', 'stock',
            'variants' => fn ($query) => $query->where('is_active', true)->with(['stock', 'attributeValues.attribute']),
            'reviews' => fn ($query) => $query->where('is_approved', true)->with('user')->latest(),
        ])->active()->where('slug', $slug)->firstOrFail();

        $categoryIds = $product->categories->pluck('id');
        $relatedProducts = Product::with(['brand', 'images', 'stock', 'variants.stock'])
            ->where('id', '!=', $product->id)
            ->active()
            ->when($categoryIds->isNotEmpty(), fn ($query) => $query->whereHas('categories', fn ($category) => $category->whereIn('categories.id', $categoryIds)))
            ->orderByDesc('is_featured')
            ->limit(4)
            ->get();

        $canReview = false;
        $userReview = null;
        if ($request->user()) {
            $canReview = Order::query()
                ->where('user_id', $request->user()->id)
                ->where('status', 'delivered')
                ->whereHas('items', fn ($query) => $query->where('product_id', $product->id))
                ->exists();
            $userReview = $product->reviews()->where('user_id', $request->user()->id)->first();
        }

        return view('products.show', compact('product', 'relatedProducts', 'canReview', 'userReview'));
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->input('q', ''));

        return $query === ''
            ? redirect()->route('products.index')
            : redirect()->route('products.index', ['search' => $query]);
    }

    public function suggestions(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        if (mb_strlen($query) < 2) {
            return response()->json(['items' => []]);
        }

        $products = Product::query()
            ->with(['images', 'brand', 'stock', 'variants.stock'])
            ->active()
            ->where(function ($builder) use ($query): void {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhereHas('brand', fn ($brand) => $brand->where('name', 'like', "%{$query}%"));
            })
            ->orderByDesc('is_featured')
            ->limit(6)
            ->get()
            ->map(fn (Product $product) => [
                'name' => $product->name,
                'brand' => $product->brand?->name,
                'url' => route('products.show', $product->slug),
                'image' => $product->main_image?->getUrl(),
                'price' => number_format($product->final_price, 0, ',', ' ').' ₽',
                'available' => $product->in_stock,
            ]);

        return response()->json(['items' => $products]);
    }
}
