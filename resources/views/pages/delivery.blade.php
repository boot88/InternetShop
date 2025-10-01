@extends('layouts.app')

@section('title', 'Доставка и оплата')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Хлебные крошки -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            @foreach($breadcrumbs as $breadcrumb)
            <li>
                @if(!$loop->last)
                <a href="{{ $breadcrumb['url'] }}" class="text-gray-500 hover:text-gray-700">{{ $breadcrumb['name'] }}</a>
                @else
                <span class="text-gray-400">{{ $breadcrumb['name'] }}</span>
                @endif
            </li>
            @if(!$loop->last)
            <li>
                <svg class="flex-shrink-0 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </li>
            @endif
            @endforeach
        </ol>
    </nav>

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Доставка и оплата</h1>

    <div class="space-y-8">
        <!-- Доставка -->
        <section>
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Способы доставки</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Курьерская доставка</h3>
                    <p class="text-gray-600 mb-2">Доставка курьером по Москве и области</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Срок: 1-2 дня</li>
                        <li>• Стоимость: от 300 руб.</li>
                        <li>• Бесплатно при заказе от 5000 руб.</li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Пункты выдачи</h3>
                    <p class="text-gray-600 mb-2">Самовывоз из пунктов выдачи заказов</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Срок: 2-3 дня</li>
                        <li>• Стоимость: от 200 руб.</li>
                        <li>• Бесплатно при заказе от 3000 руб.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Оплата -->
        <section>
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Способы оплаты</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-3">Онлайн оплата</h3>
                    <div class="flex items-center space-x-4 mb-3">
                        <div class="w-10 h-6 bg-blue-500 rounded flex items-center justify-center text-white text-xs font-bold">VISA</div>
                        <div class="w-10 h-6 bg-red-500 rounded flex items-center justify-center text-white text-xs font-bold">МИР</div>
                        <div class="w-10 h-6 bg-orange-500 rounded flex items-center justify-center text-white text-xs font-bold">MC</div>
                    </div>
                    <p class="text-gray-600 text-sm">Безопасная оплата банковской картой через защищенное соединение</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-3">Наличными</h3>
                    <p class="text-gray-600 text-sm">Оплата наличными курьеру при получении заказа или в пункте выдачи</p>
                </div>
            </div>
        </section>

        <!-- Условия -->
        <section>
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Условия доставки</h2>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold">Бесплатная доставка</h4>
                            <p class="text-gray-600 text-sm">При заказе от 5000 рублей - доставка бесплатно</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold">Отслеживание заказа</h4>
                            <p class="text-gray-600 text-sm">Все заказы можно отслеживать в личном кабинете</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold">Страхование</h4>
                            <p class="text-gray-600 text-sm">Все отправления застрахованы на полную стоимость</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection