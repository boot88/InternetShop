<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <title>{{ config('app.name', 'Laravel') }} - Интернет-магазин</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .category-badge {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 7h-4V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 5h4v2h-4V5zm10 14H4V9h16v10z"/>
                        </svg>
                        <span class="text-xl font-bold text-gray-900">{{ config('app.name', 'Laravel Store') }}</span>
                    </a>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Главная</a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Каталог</a>
                    
                    <!-- Search -->
<div class="relative">
    <form action="{{ route('products.index') }}" method="GET" class="flex">
        <input type="text" 
            name="search" 
            placeholder="Поиск товаров..." 
            value="{{ request('search') }}"
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 w-64">
        <button type="submit" class="ml-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
            Найти
        </button>
        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </form>
</div>

                    <!-- Auth Links -->
                    <div class="flex items-center space-x-4">
                        @auth
                            
							
							<!-- Cart Icon -->
                            <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ App\Http\Controllers\CartController::getCartCountStatic() }}
                                </span>
                            </a>



                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-indigo-600 font-medium">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    </div>
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" 
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Профиль</a>
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Мои заказы</a>
                                    <hr>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Выйти
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Войти</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
                                Регистрация
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Добро пожаловать в наш магазин</h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90">Лучшие товары по доступным ценам</p>
                <div class="space-x-4">
                    <a href="{{ route('products.index') }}" 
                       class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-200 inline-block">
                        Начать покупки
                    </a>
                    @guest
                    <a href="{{ route('register') }}" 
                       class="border border-white text-white px-8 py-3 rounded-lg font-medium hover:bg-white hover:text-indigo-600 transition duration-200 inline-block">
                        Создать аккаунт
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    
	
	<!-- Search Results Section -->
@if(isset($searchQuery) && $searchQuery)
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Результаты поиска: "{{ $searchQuery }}"</h2>
            <p class="text-gray-600 mt-2">Найдено товаров: {{ $searchResults ? $searchResults->count() : 0 }}</p>
        </div>

        @if($searchResults && $searchResults->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($searchResults as $product)
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                <a href="{{ route('products.show', $product->id) }}">
                    @php
                        $productImage = $product->images->first();
                    @endphp
                    
                    @if($productImage && $productImage->image_path)
                        <img src="{{ $productImage->image_path }}" 
                             alt="{{ $productImage->alt_text ?? $product->name }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </a>
                
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="category-badge px-2 py-1 rounded text-xs font-medium">
                            {{ $product->categories->first()->name ?? 'Без категории' }}
                        </span>
                        @if($product->brand)
                        <span class="text-xs text-gray-500">{{ $product->brand->name }}</span>
                        @endif
                    </div>
                    
                    <a href="{{ route('products.show', $product->id) }}" class="block">
                        <h3 class="font-semibold text-gray-900 hover:text-indigo-600 mb-2">{{ $product->name }}</h3>
                    </a>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-indigo-600">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                        
                        @auth
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 transition duration-200">
                                В корзину
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" 
                           class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-300 transition duration-200">
                            Войдите чтобы купить
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @elseif($searchQuery)
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Ничего не найдено</h3>
            <p class="text-gray-600">Попробуйте изменить поисковый запрос</p>
        </div>
        @endif
    </div>
</section>
@endif
	


    <!-- Featured Categories -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Популярные категории</h2>
            <p class="text-gray-600 mt-4">Выберите интересующую вас категорию</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @if(isset($featuredCategories) && $featuredCategories->count() > 0)
                @foreach($featuredCategories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                   class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition duration-200">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 6h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM10 4h4v2h-4V4zm10 16H4V8h16v12z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ $category->products_count }} товаров</p>
                </a>
                @endforeach
            @else
                <!-- Заглушки для категорий -->
                @foreach(['Электроника', 'Одежда', 'Книги', 'Спорт'] as $index => $categoryName)
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 6h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM10 4h4v2h-4V4zm10 16H4V8h16v12z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">{{ $categoryName }}</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ rand(5, 20) }} товаров</p>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Популярные товары</h2>
            <p class="text-gray-600 mt-4">Самые востребованные товары этой недели</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                @foreach($featuredProducts as $product)
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                    <a href="{{ route('products.show', $product->id) }}">
                        @php
                            // ПРОСТОЙ ЗАПРОС - берем первое попавшееся изображение для товара
                            $productImage = \App\Models\ProductImage::where('product_id', $product->id)->first();
                        @endphp
                        
                        @if($productImage && $productImage->image_path)
                            <img src="{{ $productImage->image_path }}" 
                                 alt="{{ $productImage->alt_text ?? $product->name }}" 
                                 class="w-full h-48 object-cover"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjNmNGY2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iNmI3MjgwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+0J3QtdGCINC40LfQvNC10L3QtdC90LjRjzwvdGV4dD48L3N2Zz4='">
                        @else
                            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </a>
                    
                    <!-- Остальной код товара без изменений -->
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="category-badge px-2 py-1 rounded text-xs font-medium">
                                @php
                                    $category = \App\Models\Category::join('category_product', 'categories.id', '=', 'category_product.category_id')
                                        ->where('category_product.product_id', $product->id)
                                        ->first();
                                @endphp
                                {{ $category->name ?? 'Без категории' }}
                            </span>
                            @php
                                $brand = null;
                                if (isset($product->brand_id)) {
                                    $brand = \App\Models\Brand::find($product->brand_id);
                                }
                            @endphp
                            @if($brand)
                            <span class="text-xs text-gray-500">{{ $brand->name }}</span>
                            @endif
                        </div>
                        
                        <a href="{{ route('products.show', $product->id) }}" class="block">
                            <h3 class="font-semibold text-gray-900 hover:text-indigo-600 mb-2">{{ $product->name }}</h3>
                        </a>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-indigo-600">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                            
                            @auth
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 transition duration-200">
                                    В корзину
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" 
                               class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-300 transition duration-200">
                                Войдите чтобы купить
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Заглушки для товаров -->
                @foreach(range(1, 8) as $i)
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="category-badge px-2 py-1 rounded text-xs font-medium">
                                Категория {{ $i }}
                            </span>
                            <span class="text-xs text-gray-500">Бренд {{ $i }}</span>
                        </div>
                        
                        <h3 class="font-semibold text-gray-900 mb-2">Пример товара {{ $i }}</h3>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">Это пример описания товара, который будет отображаться на главной странице.</p>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-indigo-600">{{ number_format(rand(1000, 10000), 0, ',', ' ') }} ₽</span>
                            
                            @auth
                            <button class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 transition duration-200">
                                В корзину
                            </button>
                            @else
                            <a href="{{ route('login') }}" 
                               class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-300 transition duration-200">
                                Войдите чтобы купить
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200">
                Смотреть все товары
            </a>
        </div>
    </div>
</section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Бесплатная доставка</h3>
                    <p class="text-gray-300">При заказе от 5000 рублей</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Гарантия качества</h3>
                    <p class="text-gray-300">Все товары проверены</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Поддержка 24/7</h3>
                    <p class="text-gray-300">Всегда готовы помочь</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ config('app.name', 'Laravel Store') }}</h3>
                    <p class="text-gray-400">Лучший интернет-магазин с широким ассортиментом товаров</p>
                </div>
                
                <div>
    <h4 class="font-semibold mb-4">Каталог</h4>
    <ul class="space-y-2 text-gray-400">
        @if(isset($categories) && $categories->count() > 0)
            @foreach($categories as $category)
            <li><a href="{{ route('products.index', ['category' => $category->id]) }}" class="hover:text-white">{{ $category->name }}</a></li>
            @endforeach
        @else
            <li><a href="#" class="hover:text-white">Электроника</a></li>
            <li><a href="#" class="hover:text-white">Одежда</a></li>
            <li><a href="#" class="hover:text-white">Книги</a></li>
            <li><a href="#" class="hover:text-white">Спорт</a></li>
        @endif
    </ul>
</div>
                
                <div>
                        <h4 class="font-semibold mb-4">Помощь</h4>
                        <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('pages.delivery') }}" class="hover:text-white">Доставка и оплата</a></li>
                        <li><a href="{{ route('pages.returns') }}" class="hover:text-white">Возврат товара</a></li>
                        <li><a href="{{ route('pages.faq') }}" class="hover:text-white">Частые вопросы</a></li>
                        <li><a href="{{ route('pages.contacts') }}" class="hover:text-white">Контакты</a></li>
                        </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Контакты</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Email: info@store.com</li>
                        <li>Телефон: +7 (999) 999-99-99</li>
                        <li>Адрес: г. Москва, ул. Примерная, д. 1</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'TechShop') }}. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>