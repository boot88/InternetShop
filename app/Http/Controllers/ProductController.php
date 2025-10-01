<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\ProductFilterRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(ProductFilterRequest $request): View
    {
        try {
            // Исправляем запрос для категорий
            $categories = Category::with(['children' => function ($query) {
                $query->where('is_active', true);
            }])->whereNull('parent_id')->where('is_active', true)->get();

            $brands = Brand::where('is_active', true)->get();

            $products = Product::with(['brand', 'categories', 'images'])
                ->where('is_active', true);

            // Фильтр по категории
            if ($request->filled('category')) {
                $category = Category::find($request->category);
                if ($category) {
                    $categoryIds = $this->getCategoryAndChildrenIds($category);
                    $products->whereHas('categories', function ($query) use ($categoryIds) {
                        $query->whereIn('categories.id', $categoryIds);
                    });
                }
            }

            // Фильтр по бренду
            if ($request->filled('brand')) {
                $products->where('brand_id', $request->brand);
            }

            // Фильтр по цене
            if ($request->filled('price_min')) {
                $products->where('price', '>=', $request->price_min);
            }

            if ($request->filled('price_max')) {
                $products->where('price', '<=', $request->price_max);
            }

            // Поиск
            if ($request->filled('search')) {
                $search = $request->search;
                $products->where(function($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('description', 'like', '%' . $search . '%')
                          ->orWhere('sku', 'like', '%' . $search . '%');
                });
            }

            // Сортировка
            $sort = $request->get('sort', 'name_asc');
            switch ($sort) {
                case 'name_desc':
                    $products->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $products->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $products->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                default:
                    $products->orderBy('name', 'asc');
            }

            $products = $products->paginate(12)->withQueryString();

            return view('products.index', compact('products', 'categories', 'brands'));

        } catch (\Exception $e) {
            // Логируем ошибку и показываем простую страницу
            \Log::error('Product index error: ' . $e->getMessage());
            
            $products = Product::where('is_active', true)->paginate(12);
            $categories = Category::whereNull('parent_id')->where('is_active', true)->get();
            $brands = Brand::where('is_active', true)->get();
            
            return view('products.index', compact('products', 'categories', 'brands'));
        }
    }
	
	
	
	

    public function show(string $identifier): View
{
    try {
        // Определяем, передан ID или slug
        $query = Product::with([
            'brand', 
            'categories', 
            'images',
            'variants' => function ($query) {
                $query->where('is_active', true);
            },
            'reviews' => function ($query) {
                $query->where('is_approved', true);
            }
        ])->where('is_active', true);

        // Если передан числовой ID
        if (is_numeric($identifier)) {
            $product = $query->where('id', $identifier)->firstOrFail();
        } else {
            // Если передан slug
            $product = $query->where('slug', $identifier)->firstOrFail();
        }

        $relatedProducts = Product::with(['brand', 'images'])
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));

    } catch (\Exception $e) {
        abort(404);
    }
}

    /**
     * Получить ID категории и всех её подкатегорий
     */
    private function getCategoryAndChildrenIds(Category $category): array
    {
        $ids = [$category->id];
        
        foreach ($category->children as $child) {
            if ($child->is_active) {
                $ids = array_merge($ids, $this->getCategoryAndChildrenIds($child));
            }
        }
        
        return $ids;
    }
	
	
	/**
     * Поиск товаров
     */
    public function search(Request $request)
    {
        $searchQuery = $request->input('q');
        
        // Если запрос пустой, перенаправляем на страницу товаров
        if (empty($searchQuery)) {
            return redirect()->route('products.index');
        }

        // Поиск товаров по названию и описанию
        $products = Product::where('name', 'LIKE', "%{$searchQuery}%")
            ->orWhere('description', 'LIKE', "%{$searchQuery}%")
            ->orWhereHas('categories', function($query) use ($searchQuery) {
                $query->where('name', 'LIKE', "%{$searchQuery}%");
            })
            ->orWhereHas('brand', function($query) use ($searchQuery) {
                $query->where('name', 'LIKE', "%{$searchQuery}%");
            })
            ->with(['images', 'categories', 'brand'])
            ->paginate(12);

        // Получаем категории для фильтрации (если нужно)
        $categories = Category::withCount('products')->get();

        return view('products.index', compact('products', 'categories', 'searchQuery'));
    }
	
	
}