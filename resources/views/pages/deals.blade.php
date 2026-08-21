{{-- resources/views/pages/deals.blade.php --}}
@extends('layouts.app')

@section('content')
@php
    /**
     * Если пока нет БД/админки — можно оставить этот демо-набор.
     * Когда появятся реальные акции — просто передай $deals из контроллера.
     */
    $deals = $deals ?? [
        [
            'title' => 'Смартфоны и аксессуары',
            'desc' => 'Скидки на популярные модели и полезные аксессуары для повседневного использования.',
            'discount' => 20,
            'badge' => 'Хит недели',
            'until' => 'До конца недели',
            'icon' => '📱',
            'link' => route('products.index'),
        ],
        [
            'title' => 'Ноутбуки для учёбы и работы',
            'desc' => 'Подборка сбалансированных моделей: автономность, экран, гарантия.',
            'discount' => 15,
            'badge' => 'Топ выбор',
            'until' => 'Ограниченное количество',
            'icon' => '💻',
            'link' => route('products.index'),
        ],
        [
            'title' => 'Умный дом',
            'desc' => 'Лампы, датчики, розетки и хабы — стартовый набор по выгодной цене.',
            'discount' => 25,
            'badge' => 'Лучшая цена',
            'until' => 'Только сегодня',
            'icon' => '🏠',
            'link' => route('products.index'),
        ],
        [
            'title' => 'Наушники и звук',
            'desc' => 'Беспроводные модели, колонки и саундбары — для спорта, дома и поездок.',
            'discount' => 30,
            'badge' => 'Суперскидка',
            'until' => '48 часов',
            'icon' => '🎧',
            'link' => route('products.index'),
        ],
        [
            'title' => 'Гейминг',
            'desc' => 'Мыши, клавиатуры, геймпады и мониторы — обнови сетап без переплаты.',
            'discount' => 18,
            'badge' => 'Для геймеров',
            'until' => 'До воскресенья',
            'icon' => '🎮',
            'link' => route('products.index'),
        ],
        [
            'title' => 'Сетевое и хранение',
            'desc' => 'Роутеры, Mesh-системы, SSD/HDD — скорость и надёжность для дома и офиса.',
            'discount' => 12,
            'badge' => 'Надёжно',
            'until' => 'Постоянная акция',
            'icon' => '🛜',
            'link' => route('products.index'),
        ],
    ];
@endphp

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header / Hero --}}
    <div class="rounded-3xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-6 sm:p-10 shadow-sm">
        <div class="grid gap-6 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs text-slate-600">
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                    Акции и спецпредложения
                </div>

                <h1 class="mt-4 text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">
                    Скидки на электронику —
                    <span class="text-indigo-600">выгодно и без лишних условий</span>
                </h1>

                <p class="mt-3 text-slate-600 leading-relaxed">
                    Выбирай категории, добавляй в корзину и оформляй заказ. Если нужна помощь — подскажем, что лучше подойдёт под твои задачи.
                </p>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-white font-medium shadow-sm hover:bg-indigo-700">
                        Смотреть товары
                    </a>
                    <a href="{{ route('contacts') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-slate-800 font-medium hover:bg-slate-50">
                        Нужна консультация
                    </a>
                </div>

                <div class="mt-6 flex flex-wrap gap-3 text-sm text-slate-600">
                    <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <span class="text-indigo-600">✓</span> Быстрая доставка
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <span class="text-indigo-600">✓</span> Гарантия и возврат
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <span class="text-indigo-600">✓</span> Оплата удобно
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Сегодняшний бонус</p>
                            <p class="mt-1 text-2xl font-semibold text-slate-900">до <span class="text-indigo-600">30%</span> скидка</p>
                        </div>
                        <div class="rounded-2xl bg-indigo-50 px-3 py-2 text-indigo-700 font-semibold">%</div>
                    </div>
                    <div class="mt-4 grid gap-3">
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <p class="text-sm text-slate-600">Как получить?</p>
                            <p class="mt-1 text-slate-800">Выбирай товары с бейджем скидки и оформляй заказ.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <p class="text-sm text-slate-600">Нужен подбор?</p>
                            <p class="mt-1 text-slate-800">Напиши нам — поможем подобрать по бюджету и задаче.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Deals grid --}}
    <div class="mt-10">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Актуальные предложения</h2>
                <p class="mt-1 text-slate-600">Карточки акций — в одном стиле с главной. Можно заменить на реальные данные из БД.</p>
            </div>
            <a href="{{ route('products.index') }}" class="hidden sm:inline-flex items-center gap-2 text-indigo-700 font-medium hover:text-indigo-800">
                В каталог
                <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($deals as $d)
                <a href="{{ $d['link'] }}" class="group block rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-xl">
                                {{ $d['icon'] ?? '✨' }}
                            </div>
                            <div>
                                <div class="inline-flex items-center gap-2">
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">
                                        {{ $d['badge'] ?? 'Акция' }}
                                    </span>
                                    <span class="text-xs text-slate-500">{{ $d['until'] ?? '' }}</span>
                                </div>
                                <h3 class="mt-2 text-lg font-semibold text-slate-900 group-hover:text-indigo-700 transition">
                                    {{ $d['title'] }}
                                </h3>
                            </div>
                        </div>

                        <div class="shrink-0">
                            <div class="rounded-2xl bg-indigo-600 text-white px-3 py-2 text-sm font-semibold">
                                -{{ (int)($d['discount'] ?? 0) }}%
                            </div>
                        </div>
                    </div>

                    <p class="mt-3 text-slate-600 leading-relaxed" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                        {{ $d['desc'] }}
                    </p>

                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-sm text-slate-600">Перейти к товарам</span>
                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-2xl border border-slate-200 bg-white group-hover:bg-slate-50">
                            <span aria-hidden="true">→</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- CTA strip --}}
    <div class="mt-10 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Хочешь скидку под твою задачу?</h3>
                <p class="mt-1 text-slate-600">Напиши, что выбираешь (телефон/ноутбук/умный дом) и ориентир по бюджету — подберём варианты.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('contacts') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-white font-medium shadow-sm hover:bg-indigo-700">
                    Написать в поддержку
                </a>
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-slate-800 font-medium hover:bg-slate-50">
                    Открыть каталог
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
