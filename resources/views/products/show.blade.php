@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Хлебные крошки -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">Главная</a></li>
            <li>></li>
            <li><a href="{{ route('products.index') }}" class="hover:text-blue-600">Каталог</a></li>
            @if($product->categories->isNotEmpty())
                <li>></li>
                <li>
                    <a href="{{ route('products.index', ['category' => $product->categories->first()->id]) }}" 
                       class="hover:text-blue-600">
                        {{ $product->categories->first()->name }}
                    </a>
                </li>
            @endif
            <li>></li>
            <li class="text-gray-900">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Изображения товара -->
        <div>
            @if($product->images->isNotEmpty())
                <div class="mb-4">
                    <img src="{{ $product->images->first()->image_path }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-96 object-cover rounded-lg">
                </div>
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $image)
                            <img src="{{ $image->image_path }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75">
                        @endforeach
                    </div>
                @endif
            @else
                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">Нет изображения</span>
                </div>
            @endif
        </div>

        <!-- Информация о товаре -->
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
            <p class="text-gray-600 mb-4">{{ $product->brand->name }}</p>
            
            <!-- Рейтинг -->
            @if($product->reviews_count > 0)
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($product->average_rating))
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <span class="ml-2 text-gray-600">
                        {{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }} отзывов)
                    </span>
                </div>
            @endif

            <!-- Цена -->
            <div class="mb-6">
                <span class="text-3xl font-bold text-blue-600">
                    {{ number_format($product->final_price, 0, ',', ' ') }} ₽
                </span>
                @if($product->has_discount)
                    <span class="text-xl text-gray-500 line-through ml-2">
                        {{ number_format($product->compare_price, 0, ',', ' ') }} ₽
                    </span>
                    <span class="ml-2 text-green-600 font-semibold">
                        Экономия {{ number_format($product->compare_price - $product->final_price, 0, ',', ' ') }} ₽
                    </span>
                @endif
            </div>

            <!-- Вариации -->
            @if($product->has_variants && $product->variants->isNotEmpty())
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Доступные варианты:</h3>
                    <div class="space-y-2">
                        @foreach($product->variants as $variant)
    <div class="border rounded p-3">
        <div class="flex justify-between items-center">
            <div>
                <span class="font-medium">{{ $variant->sku }}</span>
                @if(method_exists($variant, 'attributeValues') && $variant->attributeValues && $variant->attributeValues->isNotEmpty())
                    <span class="text-sm text-gray-600 ml-2">
                        ({{ $variant->attributeValues->pluck('value')->implode(', ') }})
                    </span>
                @endif
            </div>
            <div class="text-right">
                <span class="text-lg font-bold text-indigo-600">{{ number_format($variant->price, 0, '', ' ') }} ₽</span>
                @if($variant->old_price && $variant->old_price > $variant->price)
                    <span class="text-sm text-gray-500 line-through ml-2">{{ number_format($variant->old_price, 0, '', ' ') }} ₽</span>
                @endif
                <div class="text-sm text-gray-600 mt-1">
                    В наличии: {{ $variant->stock_quantity }} шт.
                </div>
            </div>
        </div>
        <div class="mt-3">
            <button class="add-to-cart bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 w-full" 
                    data-variant-id="{{ $variant->id }}">
                Добавить в корзину
            </button>
        </div>
    </div>
@endforeach
                    </div>
                </div>
            @endif

            <!-- Кнопка добавления в корзину -->
            <div class="mb-6">
                @if($product->in_stock)
                    <form action="{{ route('cart.add') }}" method="POST" class="flex items-center space-x-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="flex items-center border rounded">
                            <button type="button" class="px-3 py-2" onclick="decreaseQuantity()">-</button>
                            <input type="number" name="quantity" value="1" min="1" max="10" 
                                   class="w-16 text-center border-0" id="quantityInput">
                            <button type="button" class="px-3 py-2" onclick="increaseQuantity()">+</button>
                        </div>
                        
                        <button type="submit" 
                                class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                            В корзину
                        </button>
                    </form>
                @else
                    <button disabled class="bg-gray-400 text-white px-8 py-2 rounded-lg font-semibold">
                        Нет в наличии
                    </button>
                @endif
            </div>

            <!-- Краткое описание -->
            @if($product->short_description)
                <div class="mb-6">
                    <h3 class="font-semibold mb-2">Описание</h3>
                    <p class="text-gray-700">{{ $product->short_description }}</p>
                </div>
            @endif

            <!-- Характеристики -->
            <div class="mb-6">
                <h3 class="font-semibold mb-2">Характеристики</h3>
                <ul class="space-y-1 text-sm">
                    <li><strong>Бренд:</strong> {{ $product->brand->name }}</li>
                    <li><strong>Артикул:</strong> {{ $product->sku }}</li>
                    @if($product->weight)<li><strong>Вес:</strong> {{ $product->weight }} кг</li>@endif
                    @if($product->dimensions)<li><strong>Размеры:</strong> {{ $product->dimensions }}</li>@endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Полное описание -->
    @if($product->description)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Подробное описание</h2>
            <div class="prose max-w-none">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
    @endif

    <!-- Отзывы -->
    @if($product->reviews->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Отзывы ({{ $product->reviews_count }})</h2>
            <div class="space-y-6">
                @foreach($product->reviews as $review)
                    <div class="border-b pb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-semibold">{{ $review->user->name }}</span>
                            <span class="text-sm text-gray-500">{{ $review->created_at->format('d.m.Y') }}</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <div class="text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating) ★ @else ☆ @endif
                                @endfor
                            </div>
                        </div>
                        @if($review->title)
                            <h4 class="font-semibold mb-1">{{ $review->title }}</h4>
                        @endif
                        <p class="text-gray-700">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Похожие товары -->
    @if($relatedProducts->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Похожие товары</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block">
                            <img src="{{ $relatedProduct->images->first()->image_path ?? '/images/placeholder.jpg' }}" 
                                 alt="{{ $relatedProduct->name }}" 
                                 class="w-full h-48 object-cover rounded-t-lg">
                        </a>
                        <div class="p-4">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" 
                               class="block font-semibold hover:text-blue-600 mb-2">
                                {{ $relatedProduct->name }}
                            </a>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-blue-600">
                                    {{ number_format($relatedProduct->final_price, 0, ',', ' ') }} ₽
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
    function increaseQuantity() {
        const input = document.getElementById('quantityInput');
        if (parseInt(input.value) < 10) {
            input.value = parseInt(input.value) + 1;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantityInput');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endsection