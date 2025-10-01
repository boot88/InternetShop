<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function welcome(Request $request)
    {
        $searchQuery = $request->input('q');
        $searchResults = null;

        try {
            // Если есть поисковый запрос, выполняем поиск
            if ($searchQuery) {
                $searchResults = Product::with(['categories', 'brand', 'images'])
                    ->where('is_active', true)
                    ->where(function($query) use ($searchQuery) {
                        $query->where('name', 'like', '%' . $searchQuery . '%')
                              ->orWhere('description', 'like', '%' . $searchQuery . '%')
                              ->orWhere('sku', 'like', '%' . $searchQuery . '%')
                              ->orWhereHas('categories', function($q) use ($searchQuery) {
                                  $q->where('name', 'like', '%' . $searchQuery . '%');
                              })
                              ->orWhereHas('brand', function($q) use ($searchQuery) {
                                  $q->where('name', 'like', '%' . $searchQuery . '%');
                              });
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            // Получаем рекомендуемые товары
            $featuredProducts = Product::with(['categories', 'brand', 'images'])
                ->where('is_featured', true)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            $featuredCategories = Category::withCount('products')
                ->having('products_count', '>', 0)
                ->orderBy('products_count', 'desc')
                ->take(4)
                ->get();

            $categories = Category::withCount('products')
                ->having('products_count', '>', 0)
                ->get();

        } catch (\Exception $e) {
            // Если база данных еще не готова, используем заглушки
            $featuredProducts = $this->getDummyProducts();
            $featuredCategories = $this->getDummyCategories();
            $categories = $this->getDummyCategories();
            
            // Заглушка для поиска
            if ($searchQuery) {
                $searchResults = $this->getDummyProducts()->filter(function($product) use ($searchQuery) {
                    return stripos($product->name, $searchQuery) !== false || 
                           stripos($product->description, $searchQuery) !== false;
                });
            }
        }

        return view('welcome', compact(
            'featuredProducts', 
            'featuredCategories', 
            'categories',
            'searchResults',
            'searchQuery'
        ));
    }

    // Остальные методы без изменений...
    private function getDummyProducts()
    {
        return collect([
            [
                'id' => 1,
                'name' => 'Смартфон Samsung Galaxy',
                'description' => 'Современный смартфон с отличной камерой и большим экраном',
                'price' => 29990,
                'categories' => collect([['name' => 'Электроника']]),
                'brand' => ['name' => 'Samsung'],
            ],
            [
                'id' => 2,
                'name' => 'Футболка хлопковая',
                'description' => 'Удобная футболка из 100% хлопка, различные цвета',
                'price' => 1990,
                'categories' => collect([['name' => 'Одежда']]),
                'brand' => ['name' => 'Nike'],
            ],
            [
                'id' => 3,
                'name' => 'Ноутбук ASUS',
                'description' => 'Мощный ноутбук для работы и игр',
                'price' => 59990,
                'categories' => collect([['name' => 'Электроника']]),
                'brand' => ['name' => 'ASUS'],
            ],
            [
                'id' => 4,
                'name' => 'Кроссовки беговые',
                'description' => 'Легкие кроссовки для спорта и повседневной носки',
                'price' => 4990,
                'categories' => collect([['name' => 'Обувь']]),
                'brand' => ['name' => 'Adidas'],
            ],
        ]);
    }

    private function getDummyCategories()
    {
        return collect([
            ['id' => 1, 'name' => 'Электроника', 'products_count' => 15],
            ['id' => 2, 'name' => 'Одежда', 'products_count' => 23],
            ['id' => 3, 'name' => 'Книги', 'products_count' => 8],
            ['id' => 4, 'name' => 'Спорт', 'products_count' => 12],
        ]);
    }
}