@extends('layouts.app')

@section('title', 'Возврат товара')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Возврат товара</h1>

    <div class="space-y-8">
        <!-- Условия возврата -->
        <section>
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Условия возврата</h2>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold">Срок возврата</h4>
                            <p class="text-gray-600">14 дней с момента получения товара</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold">Состояние товара</h4>
                            <p class="text-gray-600">Товар должен быть в оригинальной упаковке без следов использования</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold">Документы</h4>
                            <p class="text-gray-600">Необходим чек или иное подтверждение покупки</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Процесс возврата -->
        <section>
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Процесс возврата</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 font-bold">1</span>
                    </div>
                    <h3 class="font-semibold mb-2">Заявка</h3>
                    <p class="text-gray-600 text-sm">Заполните заявку на возврат в личном кабинете</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 font-bold">2</span>
                    </div>
                    <h3 class="font-semibold mb-2">Подтверждение</h3>
                    <p class="text-gray-600 text-sm">Дождитесь подтверждения от менеджера</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 font-bold">3</span>
                    </div>
                    <h3 class="font-semibold mb-2">Отправка</h3>
                    <p class="text-gray-600 text-sm">Отправьте товар нам или передайте курьеру</p>
                </div>
            </div>
        </section>

        <!-- Не подлежит возврату -->
        <section>
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Товары, не подлежащие возврату</h2>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <span>Товары личной гигиены</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <span>Нижнее белье и чулочно-носочные изделия</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <span>Парфюмерия и косметика</span>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</div>
@endsection