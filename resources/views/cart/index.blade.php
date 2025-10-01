@extends('layouts.app')

@section('title', 'Корзина - TechZone')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Корзина покупок</h1>

        @if($cartItems->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <!-- Список товаров -->
                    <div class="space-y-6">
                        @foreach($cartItems as $item)
                        <div class="flex items-center space-x-4 border-b border-gray-200 pb-6 last:border-b-0">
                            <!-- Изображение товара -->
                            <div class="flex-shrink-0 w-20 h-20">
                                @if($item->product->images->isNotEmpty())
                                    <img src="{{ $item->product->images->first()->image_path }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover rounded-lg">
                                @else
                                    <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Информация о товаре -->
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-blue-600">
                                        {{ $item->product->name }}
                                    </a>
                                </h3>
                                @if($item->product->brand)
                                    <p class="text-gray-600 text-sm">{{ $item->product->brand->name }}</p>
                                @endif
                            </div>

                            <!-- Количество -->
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('POST')
                                    <button type="button" onclick="this.form.quantity.value = Math.max(1, parseInt(this.form.quantity.value) - 1)" 
                                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-l-lg hover:bg-gray-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <input type="number" 
                                           name="quantity" 
                                           value="{{ $item->quantity }}" 
                                           min="1" 
                                           class="w-12 h-8 text-center border-t border-b border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button type="button" onclick="this.form.quantity.value = parseInt(this.form.quantity.value) + 1" 
                                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-r-lg hover:bg-gray-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                    <button type="submit" class="ml-2 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Обновить
                                    </button>
                                </form>
                            </div>

                            <!-- Цена -->
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽
                                </p>
                                <p class="text-gray-600 text-sm">
                                    {{ number_format($item->price, 0, ',', ' ') }} ₽ за шт.
                                </p>
                            </div>

                            <!-- Удаление -->
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200"
                                        onclick="return confirm('Удалить товар из корзины?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>

                    <!-- Итого -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-2xl font-bold text-gray-900">Итого:</span>
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($total, 0, ',', ' ') }} ₽</span>
                        </div>

                        <!-- Кнопки действий -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('products.index') }}" 
                               class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-medium text-center transition-colors duration-200">
                                Продолжить покупки
                            </a>
                            
                            <form action="{{ route('cart.clear') }}" method="POST" class="flex-1">
                                @csrf
                                @method('POST')
                                <button type="submit" 
                                        class="w-full bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-medium transition-colors duration-200"
                                        onclick="return confirm('Очистить всю корзину?')">
                                    Очистить корзину
                                </button>
                            </form>
                            
                            <button class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium transition-colors duration-200">
                                Оформить заказ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Пустая корзина -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Ваша корзина пуста</h2>
                <p class="text-gray-600 mb-8">Добавьте товары из каталога, чтобы сделать заказ</p>
                <a href="{{ route('products.index') }}" 
                   class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-medium inline-block transition-colors duration-200">
                    Перейти в каталог
                </a>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session('success') }}');
    });
</script>
@endif
@endsection