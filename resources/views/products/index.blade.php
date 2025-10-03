@extends('layouts.app')

@section('title', 'Каталог товаров - TechZone')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Хедер каталога -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        @if(request('search'))
                            Результаты поиска: "{{ request('search') }}"
                        @else
                            Каталог товаров
                        @endif
                    </h1>
                    <p class="text-gray-600 mt-2">Найдено товаров: {{ $products->total() }}</p>
                </div>
                
                <!-- Сортировка -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Сортировка:</span>
                    <select onchange="window.location.href = this.value" 
                            class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @php
                            $currentSort = request('sort', 'name_asc');
                            $sortOptions = [
                                'name_asc' => 'По названию (А-Я)',
                                'name_desc' => 'По названию (Я-А)',
                                'price_asc' => 'Сначала дешевле',
                                'price_desc' => 'Сначала дороже',
                                'newest' => 'Сначала новые'
                            ];
                        @endphp
                        @foreach($sortOptions as $value => $label)
                            <option value="{{ route('products.index', ['sort' => $value] + request()->except('sort')) }}" 
                                    {{ $currentSort == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Левая колонка - фильтры -->
            <aside class="lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <!-- Поиск -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('products.index') }}" id="searchForm">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       placeholder="Поиск товаров..." 
                                       value="{{ request('search') }}" 
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       id="searchInput">
                                <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            @foreach(request()->except('search', 'page') as $key => $value)
                                @if(is_array($value))
                                    @foreach($value as $arrayValue)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $arrayValue }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                        </form>
                    </div>

                    <!-- Категории -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            Категории
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('products.index', request()->except('category', 'page')) }}" 
                                   class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-50 {{ !request('category') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700' }}">
                                    <span>Все категории</span>
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                        {{ \App\Models\Product::where('is_active', true)->count() }}
                                    </span>
                                </a>
                            </li>
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('products.index', ['category' => $category->id] + request()->except('category', 'page')) }}" 
                                       class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-50 {{ request('category') == $category->id ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700' }}">
                                        <span class="flex items-center">
                                            @if($category->children->isNotEmpty())
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            @endif
                                            {{ $category->name }}
                                        </span>
                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                            {{ $category->products_count }}
                                        </span>
                                    </a>
                                    
                                    @if($category->children->isNotEmpty())
                                        <ul class="ml-6 mt-1 space-y-1">
                                            @foreach($category->children as $child)
                                                @if($child->is_active)
                                                    <li>
                                                        <a href="{{ route('products.index', ['category' => $child->id] + request()->except('category', 'page')) }}" 
                                                           class="flex justify-between items-center px-3 py-1.5 rounded-lg hover:bg-gray-50 {{ request('category') == $child->id ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                                                            <span class="text-sm">{{ $child->name }}</span>
                                                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">
                                                                {{ $child->products_count }}
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Бренды -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg text-gray-900 mb-4">Бренды</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('products.index', request()->except('brand', 'page')) }}" 
                                   class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-50 {{ !request('brand') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700' }}">
                                    <span>Все бренды</span>
                                </a>
                            </li>
                            @foreach($brands as $brand)
                                <li>
                                    <a href="{{ route('products.index', ['brand' => $brand->id] + request()->except('brand', 'page')) }}" 
                                       class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-50 {{ request('brand') == $brand->id ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700' }}">
                                        <span>{{ $brand->name }}</span>
                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                            {{ $brand->products_count }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Фильтр по цене -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg text-gray-900 mb-4">Цена, ₽</h3>
                        <form method="GET" action="{{ route('products.index') }}" class="space-y-3" id="priceForm">
                            <div class="flex gap-3">
                                <input type="number" 
                                       name="price_min" 
                                       placeholder="От" 
                                       value="{{ request('price_min') }}" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <input type="number" 
                                       name="price_max" 
                                       placeholder="До" 
                                       value="{{ request('price_max') }}" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            @foreach(request()->except(['price_min', 'price_max', 'page']) as $key => $value)
                                @if(is_array($value))
                                    @foreach($value as $arrayValue)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $arrayValue }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium text-sm">
                                Применить
                            </button>
                        </form>
                    </div>

                    <!-- Активные фильтры -->
                    @if(request()->anyFilled(['category', 'brand', 'price_min', 'price_max', 'search']))
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">Активные фильтры:</h4>
                        <div class="flex flex-wrap gap-2">
                            @if(request('search'))
                                <span class="inline-flex items-center bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">
                                    Поиск: "{{ request('search') }}"
                                    <a href="{{ route('products.index', request()->except('search', 'page')) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        ×
                                    </a>
                                </span>
                            @endif
                            @if(request('category') && $activeCategory = \App\Models\Category::find(request('category')))
                                <span class="inline-flex items-center bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">
                                    Категория: {{ $activeCategory->name }}
                                    <a href="{{ route('products.index', request()->except('category', 'page')) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        ×
                                    </a>
                                </span>
                            @endif
                            @if(request('brand') && $activeBrand = \App\Models\Brand::find(request('brand')))
                                <span class="inline-flex items-center bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">
                                    Бренд: {{ $activeBrand->name }}
                                    <a href="{{ route('products.index', request()->except('brand', 'page')) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        ×
                                    </a>
                                </span>
                            @endif
                            @if(request('price_min'))
                                <span class="inline-flex items-center bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">
                                    Цена от: {{ request('price_min') }}₽
                                    <a href="{{ route('products.index', request()->except('price_min', 'page')) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        ×
                                    </a>
                                </span>
                            @endif
                            @if(request('price_max'))
                                <span class="inline-flex items-center bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">
                                    Цена до: {{ request('price_max') }}₽
                                    <a href="{{ route('products.index', request()->except('price_max', 'page')) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        ×
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Сброс фильтров -->
                    @if(request()->anyFilled(['category', 'brand', 'price_min', 'price_max', 'search']))
                        <a href="{{ route('products.index') }}" 
                           class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 font-medium text-sm text-center block">
                            Сбросить все фильтры
                        </a>
                    @endif
                </div>
            </aside>

            <!-- Правая колонка - товары -->
            <main class="flex-1">
                @if($products->count() > 0)
                    <!-- Сетка товаров -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 hover:translate-y-[-2px]">
                                <a href="{{ route('products.show', $product->slug) }}" class="block relative">
                                    @if($product->images->isNotEmpty())
                                        <img src="{{ $product->images->first()->image_path }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-64 object-cover rounded-t-xl">
                                    @else
                                        <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-t-xl flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Бейдж акции -->
                                    @if($product->compare_price && $product->compare_price > $product->price)
                                        <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                            -{{ number_format((1 - $product->price / $product->compare_price) * 100, 0) }}%
                                        </span>
                                    @endif
                                </a>
                                
                                <div class="p-5">
                                    <!-- Бренд -->
                                    @if($product->brand)
                                        <div class="text-sm text-gray-500 mb-1">{{ $product->brand->name }}</div>
                                    @endif
                                    
                                    <!-- Название -->
                                    <a href="{{ route('products.show', $product->slug) }}" 
                                       class="block font-semibold text-gray-900 hover:text-blue-600 mb-2 line-clamp-2 leading-tight">
                                        {{ $product->name }}
                                    </a>
                                    
                                    <!-- Краткое описание -->
                                    @if($product->short_description)
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                            {{ $product->short_description }}
                                        </p>
                                    @endif
                                    
                                    <!-- Рейтинг -->
                                    @if($product->reviews_count > 0)
                                        <div class="flex items-center mb-3">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($product->average_rating))
                                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 fill-current text-gray-300" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-600 ml-2">
                                                {{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }})
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <!-- Цена -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="text-2xl font-bold text-gray-900">
                                                {{ number_format($product->final_price, 0, ',', ' ') }} ₽
                                            </span>
                                            @if($product->compare_price && $product->compare_price > $product->price)
                                                <span class="text-lg text-gray-500 line-through ml-2">
                                                    {{ number_format($product->compare_price, 0, ',', ' ') }} ₽
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Статус и кнопка -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium px-2 py-1 rounded-full {{ $product->in_stock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->in_stock ? 'В наличии' : 'Нет в наличии' }}
                                        </span>
                                        
                                        <div class="flex space-x-2">
                                           

										   @auth
    @if($product->in_stock)
    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
        @csrf
        <input type="hidden" name="quantity" value="1">
        <button type="submit" 
                class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 font-medium text-sm transition-colors duration-200 flex items-center add-to-cart-btn">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            В корзину
        </button>
    </form>
    @endif
@else
    <a href="{{ route('login') }}" 
       class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-300 font-medium text-sm transition-colors duration-200">
        Войдите чтобы купить
    </a>
@endauth
                                            
											
											<!--a href="{{ route('products.show', $product->slug) }}" 
                                               class="border border-blue-600 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-50 font-medium text-sm transition-colors duration-200">
                                                Подробнее
                                            </a-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Пагинация -->
                    <div class="mt-8">
                        {{ $products->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                @else
                    <!-- Нет товаров -->
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            @if(request('search'))
                                По запросу "{{ request('search') }}" ничего не найдено
                            @else
                                Товары не найдены
                            @endif
                        </h3>
                        <p class="text-gray-600 mb-6">Попробуйте изменить параметры фильтрации или поиска</p>
                        @if(request()->anyFilled(['category', 'brand', 'price_min', 'price_max', 'search']))
                            <a href="{{ route('products.index') }}" 
                               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium inline-block">
                                Показать все товары
                            </a>
                        @endif
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>

<script>
    // Автопоиск при вводе
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchForm.submit();
            }, 500);
        });
        
        // Очистка таймера при уходе со страницы
        window.addEventListener('beforeunload', function() {
            clearTimeout(searchTimeout);
        });
    });
	
	
	document.addEventListener('DOMContentLoaded', function() {
    // Обработка всех форм добавления в корзину
    const cartForms = document.querySelectorAll('.add-to-cart-form');
    
    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('.add-to-cart-btn');
            const originalText = submitButton.innerHTML;
            
            // Показываем индикатор загрузки
            submitButton.innerHTML = 'Добавляем...';
            submitButton.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Обновляем счетчик корзины - ВАЖНО: принудительно обновляем
                    updateCartCount(data.cart_count);
                    // Дополнительно обновляем через статический метод
                    updateCartCountFromServer();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Ошибка при добавлении в корзину', 'error');
            })
            .finally(() => {
                // Восстанавливаем кнопку
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    });
    
    function showNotification(message, type = 'success') {
        // Создаем уведомление
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Удаляем уведомление через 3 секунды
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    function updateCartCount(count) {
        // Обновляем счетчик корзины в навигации
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(element => {
            element.textContent = count;
        });
    }
    
    // Новая функция: обновление счетчика через серверный метод
    function updateCartCountFromServer() {
        fetch('/cart/count', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.count !== undefined) {
                updateCartCount(data.count);
            }
        })
        .catch(error => {
            console.error('Error fetching cart count:', error);
        });
    }
});
	
	
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection