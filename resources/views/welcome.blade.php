<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

  <title>{{ config('app.name', 'TechZone') }} — интернет-магазин электроники</title>

  <!-- Tailwind CDN (работает и на хостинге без npm/vite) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <style>
    html { scroll-behavior: smooth; }
    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .line-clamp-3 {
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
  </style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900 overflow-x-hidden">

  <!-- Topbar -->
  <div class="hidden md:block border-b bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-2 text-sm text-slate-600">
        <div class="flex items-center gap-4">
          <span class="inline-flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-4.35-7-10a7 7 0 0 1 14 0c0 5.65-7 10-7 10z"/><path d="M12 11a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
            Новосибирск и область
          </span>
          <span class="inline-flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92V19a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 3 4.18 2 2 0 0 1 5 2h2.09a2 2 0 0 1 2 1.72c.12.86.31 1.7.57 2.5a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.58-1.12a2 2 0 0 1 2.11-.45c.8.26 1.64.45 2.5.57A2 2 0 0 1 22 16.92z"/></svg>
            Поддержка: 8 (800) 122‑37‑37
          </span>
        </div>
        <div class="flex items-center gap-4">
          <a class="hover:text-slate-900" href="{{ route('delivery') }}">Доставка</a>
          <a class="hover:text-slate-900" href="{{ route('returns') }}">Возврат</a>
          <a class="hover:text-slate-900" href="{{ route('contacts') }}">Контакты</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Header -->
  <header class="sticky top-0 z-50 border-b bg-white/90 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="{ mobileOpen:false }">
      <div class="flex h-16 items-center justify-between gap-3">

        <!-- Left: logo + burger -->
        <div class="flex items-center gap-3">
          <button class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50"
                  @click="mobileOpen = !mobileOpen" aria-label="Меню">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>

          <a href="{{ url('/') }}" class="flex items-center gap-2">
            <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-indigo-600 to-fuchsia-600"></div>
            <div class="leading-tight">
              <div class="text-base font-semibold tracking-tight">{{ config('app.name', 'TechZone') }}</div>
              <div class="hidden sm:block text-xs text-slate-500">Электроника • Гарантия • Доставка</div>
            </div>
          </a>
        </div>

        <!-- Center: search -->
        <div class="hidden md:block flex-1 max-w-2xl">
          
		  <form action="{{ route('products.index') }}" method="GET" class="relative">
  <input name="search"
         value="{{ request('search') ?? request('q') }}"
         placeholder="Поиск: смартфоны, ноутбуки, наушники…"
         class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 pr-12 text-sm outline-none
                focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">

  <button type="submit"
          class="absolute right-2 top-1/2 -translate-y-1/2 rounded-xl px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
    Найти
  </button>
</form>
       

	   </div>

        <!-- Right: actions -->
        <div class="flex items-center gap-2 sm:gap-3">
          <a href="{{ route('products.index') }}" class="hidden lg:inline-flex rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium hover:bg-slate-50">
            Каталог
          </a>

          <a href="{{ route('cart.index') }}" class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50" aria-label="Корзина">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 6h15l-1.5 9h-13z"/>
              <path d="M6 6l-2-2H1"/>
              <path d="M9 21a1 1 0 1 0 0-2 1 1 0 0 0 0 2zM18 21a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
            </svg>
            <span class="absolute -right-1 -top-1 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-indigo-600 px-1 text-xs font-semibold text-white" id="cartCountBadge">
              {{ App\Http\Controllers\CartController::getCartCountStatic() }}
            </span>
          </a>

          @auth
            <div class="relative" x-data="{ open:false }">
              <button @click="open = !open" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 hover:bg-slate-50">
                <div class="grid h-8 w-8 place-items-center rounded-xl bg-indigo-50 text-indigo-700 font-semibold">
                  {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="hidden sm:block text-sm font-medium max-w-[140px] truncate">{{ Auth::user()->name }}</span>
                <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
              </button>

              <div x-show="open" x-transition @click.away="open=false" class="absolute right-0 mt-2 w-52 overflow-hidden rounded-2xl border bg-white shadow-lg">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm hover:bg-slate-50">Профиль</a>
                <a href="{{ route('orders.index') }}" class="block px-4 py-3 text-sm hover:bg-slate-50">Мои заказы</a>
                <div class="h-px bg-slate-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="block w-full px-4 py-3 text-left text-sm hover:bg-slate-50">Выйти</button>
                </form>
              </div>
            </div>
          @else
            <a href="{{ route('login') }}" class="sm:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50" aria-label="Войти или зарегистрироваться">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M20 21a8 8 0 0 0-16 0"/>
                <circle cx="12" cy="8" r="4"/>
              </svg>
            </a>
            <a href="{{ route('login') }}" class="hidden sm:inline-flex rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium hover:bg-slate-50">Войти</a>
            <a href="{{ route('register') }}" class="hidden sm:inline-flex rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Регистрация</a>
          @endauth
        </div>
      </div>

      <!-- Mobile: search + menu -->
      <div class="md:hidden pb-3">
        <form action="{{ route('products.index') }}" method="GET" class="relative">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Поиск товаров…"
                 class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-11 py-3 text-sm outline-none focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100" />
          <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </form>

        <div x-show="mobileOpen" x-transition class="mt-3 grid gap-2 rounded-2xl border border-slate-200 bg-white p-3">
          <a class="rounded-xl px-3 py-2 text-sm font-medium hover:bg-slate-50" href="{{ url('/') }}">Главная</a>
          <a class="rounded-xl px-3 py-2 text-sm font-medium hover:bg-slate-50" href="{{ route('products.index') }}">Каталог</a>
          <a class="rounded-xl px-3 py-2 text-sm font-medium hover:bg-slate-50" href="{{ route('delivery') }}">Доставка</a>
          <a class="rounded-xl px-3 py-2 text-sm font-medium hover:bg-slate-50" href="{{ route('returns') }}">Возврат</a>
          <a class="rounded-xl px-3 py-2 text-sm font-medium hover:bg-slate-50" href="{{ route('contacts') }}">Контакты</a>
        </div>
      </div>

    </div>
  </header>

  <!-- Hero -->
  <section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600"></div>
    <div class="absolute inset-0 opacity-15" style="background-image: radial-gradient(circle at 20% 20%, white 2px, transparent 2px), radial-gradient(circle at 80% 40%, white 2px, transparent 2px); background-size: 44px 44px;"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-10 py-12 md:grid-cols-2 md:py-16">
        <div class="text-white">
          <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm">
            <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
            Хиты, новинки и акции — каждый день
          </div>
          <h1 class="mt-5 text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">
            Электроника, которую приятно покупать
          </h1>
          <p class="mt-4 max-w-xl text-white/90 sm:text-lg">
            Смартфоны, ноутбуки, аксессуары и техника для дома. Быстрая доставка, гарантия и поддержка.
          </p>

          <div class="mt-7 flex flex-col gap-3 sm:flex-row">
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-indigo-700 hover:bg-slate-50">
              Перейти в каталог
            </a>
            <a href="#deals" class="inline-flex items-center justify-center rounded-2xl border border-white/30 bg-white/10 px-6 py-3 text-sm font-semibold text-white hover:bg-white/15">
              Смотреть акции
            </a>
          </div>

          <div class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-3">
            <div class="rounded-2xl bg-white/10 p-4">
              <div class="text-2xl font-semibold">1–2 дня</div>
              <div class="text-sm text-white/80">доставка по городу</div>
            </div>
            <div class="rounded-2xl bg-white/10 p-4">
              <div class="text-2xl font-semibold">14 дней</div>
              <div class="text-sm text-white/80">на возврат</div>
            </div>
            <div class="rounded-2xl bg-white/10 p-4">
              <div class="text-2xl font-semibold">Гарантия</div>
              <div class="text-sm text-white/80">на всю технику</div>
            </div>
          </div>
        </div>

        <div class="md:pl-8">
          <div class="rounded-3xl bg-white/10 p-5 ring-1 ring-white/20">
            <div class="rounded-2xl bg-white p-5 shadow-xl">
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-sm text-slate-500">Подборка дня</div>
                  <div class="text-lg font-semibold">Лучшие предложения</div>
                </div>
                <div class="rounded-2xl bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-700">-10% на аксессуары</div>
              </div>

              <div class="mt-4 grid grid-cols-2 gap-3">
                <a href="{{ route('products.index', ['category' => '1']) }}" class="group overflow-hidden rounded-2xl border border-slate-200 p-4 hover:border-indigo-200 hover:bg-indigo-50/40">
                  <div class="flex items-center gap-3 min-w-0">
                    <div class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 group-hover:bg-white">
                      <svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="7" y="2" width="10" height="20" rx="2"/><path d="M12 18h.01"/></svg>
                    </div>
                    <div class="min-w-0">
                      <div class="text-sm font-semibold leading-snug break-words">Смартфоны</div>
                      <div class="text-xs text-slate-500 leading-snug break-words">хиты сезона</div>
                    </div>
                  </div>
                </a>

                <a href="{{ route('products.index', ['category' => '2']) }}" class="group overflow-hidden rounded-2xl border border-slate-200 p-4 hover:border-indigo-200 hover:bg-indigo-50/40">
                  <div class="flex items-center gap-3 min-w-0">
                    <div class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 group-hover:bg-white">
                      <svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M2 20h20"/></svg>
                    </div>
                    <div class="min-w-0">
                      <div class="text-sm font-semibold leading-snug break-words">Ноутбуки</div>
                      <div class="text-xs text-slate-500 leading-snug break-words">для дома и офиса</div>
                    </div>
                  </div>
                </a>

                <a href="{{ route('products.index', ['category' => '20']) }}" class="group overflow-hidden rounded-2xl border border-slate-200 p-4 hover:border-indigo-200 hover:bg-indigo-50/40">
                  <div class="flex items-center gap-3 min-w-0">
                    <div class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 group-hover:bg-white">
                      <svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                    </div>
                    <div class="min-w-0">
                      <div class="text-sm font-semibold leading-snug break-words">Аудио</div>
                      <div class="text-xs text-slate-500 leading-snug break-words">наушники и колонки</div>
                    </div>
                  </div>
                </a>

                <a href="{{ route('products.index', ['category' => '4']) }}" class="group overflow-hidden rounded-2xl border border-slate-200 p-4 hover:border-indigo-200 hover:bg-indigo-50/40">
                  <div class="flex items-center gap-3 min-w-0">
                    <div class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 group-hover:bg-white">
                      <svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v8"/><path d="M8 6h8"/><path d="M6 10h12l-1 12H7L6 10z"/></svg>
                    </div>
                    <div class="min-w-0">
                      <div class="text-sm font-semibold leading-snug break-words">Аксессуары</div>
                      <div class="text-xs text-slate-500 leading-snug break-words">зарядки, чехлы</div>
                    </div>
                  </div>
                </a>
              </div>

              <div class="mt-5 flex flex-col gap-3 rounded-2xl bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm">
                  <div class="font-semibold">Нужна помощь с выбором?</div>
                  <div class="text-slate-600">Подскажем по характеристикам и совместимости.</div>
                </div>
                <a href="{{ route('contacts') }}" class="w-full sm:w-auto text-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Связаться</a>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Benefits -->
  <section class="py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
          <div class="mb-3 grid h-12 w-12 place-items-center rounded-2xl bg-indigo-50 text-indigo-700">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h13v10H3z"/><path d="M16 10h4l1 2v5h-5z"/><path d="M7 17a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM18 17a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>
          </div>
          <div class="font-semibold">Быстрая доставка</div>
          <div class="mt-1 text-sm text-slate-600">Курьером или в пункт выдачи — как удобно.</div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
          <div class="mb-3 grid h-12 w-12 place-items-center rounded-2xl bg-emerald-50 text-emerald-700">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1l3 5 6 1-4 4 1 6-6-3-6 3 1-6-4-4 6-1z"/></svg>
          </div>
          <div class="font-semibold">Официальная гарантия</div>
          <div class="mt-1 text-sm text-slate-600">Чеки, документы, гарантийное обслуживание.</div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
          <div class="mb-3 grid h-12 w-12 place-items-center rounded-2xl bg-fuchsia-50 text-fuchsia-700">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V6l-8-4-8 4v6c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-5"/></svg>
          </div>
          <div class="font-semibold">Проверка перед отправкой</div>
          <div class="mt-1 text-sm text-slate-600">Комплектация, упаковка, контроль качества.</div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
          <div class="mb-3 grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-800">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg>
          </div>
          <div class="font-semibold">Поддержка</div>
          <div class="mt-1 text-sm text-slate-600">Поможем выбрать, настроить, оформить заказ.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Deals / Featured products -->
  <section id="deals" class="py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex items-end justify-between gap-4">
        <div>
          <h2 class="text-2xl font-semibold tracking-tight">Популярное и выгодное</h2>
          <p class="mt-1 text-slate-600">Подборка товаров, которые чаще всего покупают.</p>
        </div>
        <a href="{{ route('products.index') }}" class="hidden sm:inline-flex rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium hover:bg-white">
          Весь каталог
        </a>
      </div>

      @php
        // Если контроллер не отдаёт данные — просто покажем пустую сетку с подсказкой.
        $popular = $popularProducts ?? collect();
        $new     = $newProducts ?? collect();
        $sale    = $saleProducts ?? collect();
      @endphp

      {{-- Популярные --}}
@if($popular->count())
  <div class="mt-6">
    <div class="flex items-end justify-between gap-4">
      <div>
        <h2 class="text-2xl font-semibold tracking-tight">Популярные товары</h2>
        <p class="mt-1 text-slate-600">Чаще всего покупают (по заказам).</p>
      </div>
      <a href="{{ route('products.index') }}" class="hidden sm:inline-flex rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium hover:bg-white">
        Весь каталог
      </a>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
      @foreach($popular as $product)
        @php $img = $product->images->sortByDesc('is_main')->sortBy('order')->first(); @endphp
        {{-- карточка (оставь твою текущую разметку, только с правками slug + img) --}}
        <div class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 hover:shadow-md transition">
          <a href="{{ route('products.show', $product->slug) }}" class="block">
            <div class="aspect-[4/3] bg-slate-50">
              @if($img)
                <img src="{{ method_exists($img, 'getUrl') ? $img->getUrl() : $img->image_path }}" alt="{{ $img->alt_text ?? $product->name }}" class="h-full w-full object-cover" loading="lazy" />
              @else
                <div class="h-full w-full grid place-items-center">
                  <svg class="h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 16l5-5a2 2 0 0 1 3 0l2 2"/><path d="M14 13l1-1a2 2 0 0 1 3 0l3 3"/></svg>
                </div>
              @endif
            </div>
            <div class="p-4">
              <div class="flex items-center justify-between gap-2 min-w-0">
                <div class="min-w-0 flex-1 text-xs text-slate-500 truncate">
                  {{ $product->brand->name ?? ($product->categories->first()->name ?? 'Товар') }}
                </div>
                <span class="inline-flex rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700">в наличии</span>
              </div>
              <div class="mt-2 text-sm font-semibold group-hover:text-indigo-700 line-clamp-2">{{ $product->name }}</div>
              <div class="mt-3 flex items-center justify-between">
                <div class="text-lg font-semibold text-slate-900">{{ number_format($product->price, 0, ',', ' ') }} ₽</div>
                <form method="POST" action="{{ route('cart.add', $product->id) }}" class="inline" data-add-to-cart>
                  @csrf
                  <button type="submit" data-add-to-cart-button class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                    В корзину
                  </button>
                </form>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
@endif

{{-- Новинки --}}
@if($new->count())
  <div class="mt-10">
    <div>
      <h2 class="text-2xl font-semibold tracking-tight">Новинки</h2>
      <p class="mt-1 text-slate-600">Свежие поступления.</p>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
      @foreach($new as $product)
        @php $img = $product->images->sortByDesc('is_main')->sortBy('order')->first(); @endphp
        {{-- та же карточка --}}
        <div class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 hover:shadow-md transition">
          <a href="{{ route('products.show', $product->slug) }}" class="block">
            <div class="aspect-[4/3] bg-slate-50">
              @if($img)
                <img src="{{ method_exists($img, 'getUrl') ? $img->getUrl() : $img->image_path }}" alt="{{ $img->alt_text ?? $product->name }}" class="h-full w-full object-cover" loading="lazy" />
              @else
                <div class="h-full w-full grid place-items-center">
                  <svg class="h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 16l5-5a2 2 0 0 1 3 0l2 2"/><path d="M14 13l1-1a2 2 0 0 1 3 0l3 3"/></svg>
                </div>
              @endif
            </div>
            <div class="p-4">
              <div class="flex items-center justify-between gap-2 min-w-0">
                <div class="min-w-0 flex-1 text-xs text-slate-500 truncate">
                  {{ $product->brand->name ?? ($product->categories->first()->name ?? 'Товар') }}
                </div>
                <span class="inline-flex rounded-full bg-indigo-50 px-2 py-1 text-[11px] font-semibold text-indigo-700">новинка</span>
              </div>
              <div class="mt-2 text-sm font-semibold group-hover:text-indigo-700 line-clamp-2">{{ $product->name }}</div>
              <div class="mt-3 flex items-center justify-between">
                <div class="text-lg font-semibold text-slate-900">{{ number_format($product->price, 0, ',', ' ') }} ₽</div>
                <form method="POST" action="{{ route('cart.add', $product->id) }}" class="inline" data-add-to-cart>
                  @csrf
                  <button type="submit" data-add-to-cart-button class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">В корзину</button>
                </form>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
@endif

{{-- Акции --}}
@if($sale->count())
  <div class="mt-10">
    <div>
      <h2 class="text-2xl font-semibold tracking-tight">Акции</h2>
      <p class="mt-1 text-slate-600">Скидки там, где старая цена больше новой.</p>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
      @foreach($sale as $product)
        @php $img = $product->images->sortByDesc('is_main')->sortBy('order')->first(); @endphp
        <div class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 hover:shadow-md transition">
          <a href="{{ route('products.show', $product->slug) }}" class="block">
            <div class="aspect-[4/3] bg-slate-50 relative">
              @if($img)
                <img src="{{ method_exists($img, 'getUrl') ? $img->getUrl() : $img->image_path }}" alt="{{ $img->alt_text ?? $product->name }}" class="h-full w-full object-cover" loading="lazy" />
              @else
                <div class="h-full w-full grid place-items-center">
                  <svg class="h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 16l5-5a2 2 0 0 1 3 0l2 2"/><path d="M14 13l1-1a2 2 0 0 1 3 0l3 3"/></svg>
                </div>
              @endif
              <span class="absolute left-3 top-3 inline-flex rounded-full bg-rose-600 px-2 py-1 text-[11px] font-semibold text-white">скидка</span>
            </div>
            <div class="p-4">
              <div class="flex items-center justify-between gap-2 min-w-0">
                <div class="min-w-0 flex-1 text-xs text-slate-500 truncate">
                  {{ $product->brand->name ?? ($product->categories->first()->name ?? 'Товар') }}
                </div>
              </div>

              <div class="mt-2 text-sm font-semibold group-hover:text-indigo-700 line-clamp-2">{{ $product->name }}</div>

              <div class="mt-3 flex items-center justify-between">
                <div>
                  <div class="text-lg font-semibold text-slate-900">{{ number_format($product->price, 0, ',', ' ') }} ₽</div>
                  <div class="text-xs text-slate-400 line-through">{{ number_format($product->compare_price, 0, ',', ' ') }} ₽</div>
                </div>
                <form method="POST" action="{{ route('cart.add', $product->id) }}" class="inline" data-add-to-cart>
                  @csrf
                  <button type="submit" data-add-to-cart-button class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">В корзину</button>
                </form>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
@endif

@if(!$popular->count() && !$new->count() && !$sale->count())
  {{-- твоя текущая заглушка --}}
  <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
    <div class="text-sm text-slate-600">
      На главной обычно показывают <b>популярные товары, новинки и акции</b>. Сейчас данные для витрины не переданы в шаблон.
    </div>
    <div class="mt-4">
      <a href="{{ route('products.index') }}" class="inline-flex rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
        Открыть каталог
      </a>
    </div>
  </div>
@endif

      <div class="mt-6 sm:hidden">
        <a href="{{ route('products.index') }}" class="inline-flex w-full justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold hover:bg-slate-50">
          Весь каталог
        </a>
      </div>
    </div>
  </section>

  <!-- Newsletter -->
  <section class="py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 to-slate-800 p-8 text-white ring-1 ring-black/10">
        <div class="grid gap-6 md:grid-cols-2 md:items-center">
          <div>
            <h3 class="text-xl font-semibold">Получайте акции и новинки</h3>
            <p class="mt-2 text-white/80">Без спама: только скидки и полезные подборки.</p>
          </div>
          <form class="flex flex-col gap-3 sm:flex-row" action="#" method="POST" onsubmit="event.preventDefault(); alert('Подписка — Оформлена');">
            <input type="email" required placeholder="email@example.com" class="w-full rounded-2xl bg-white/10 px-4 py-3 text-sm outline-none ring-1 ring-white/15 focus:ring-4 focus:ring-indigo-300/30" />
            <button class="rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-100">Подписаться</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="border-t bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
      <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
        <div>
          <div class="text-base font-semibold">{{ config('app.name', 'TechZone') }}</div>
          <p class="mt-2 text-sm text-slate-600">Интернет-магазин электроники и аксессуаров.</p>
          <p class="mt-3 text-xs text-slate-500">© {{ date('Y') }} Все права защищены.</p>
        </div>
        <div>
          <div class="text-sm font-semibold">Покупателям</div>
          <div class="mt-3 grid gap-2 text-sm">
            <a class="text-slate-600 hover:text-slate-900" href="{{ route('delivery') }}">Доставка</a>
            <a class="text-slate-600 hover:text-slate-900" href="{{ route('returns') }}">Возврат</a>
            <a class="text-slate-600 hover:text-slate-900" href="{{ route('faq') }}">FAQ</a>
            <a class="text-slate-600 hover:text-slate-900" href="{{ route('contacts') }}">Контакты</a>
          </div>
        </div>
        <div>
          <div class="text-sm font-semibold">Каталог</div>
          <div class="mt-3 grid gap-2 text-sm">
            <a class="text-slate-600 hover:text-slate-900" href="{{ route('products.index') }}">Все товары</a>
            <a class="text-slate-600 hover:text-slate-900" href="{{ route('deals') }}">Акции</a>
          </div>
        </div>
        <div>
          <div class="text-sm font-semibold">Поддержка</div>
          <div class="mt-3 text-sm text-slate-600">
            <div>Тел.: 8 (800) 122‑37‑37</div>
            <div class="mt-1">Email: support@Marketing.com</div>
            <div class="mt-3 text-xs text-slate-500">* Контакты можно вынести через администратора.</div>
          </div>
        </div>
      </div>
    </div>
  </footer>


  <!-- Toast -->
  <div id="toastWrap" class="fixed top-4 right-4 z-[9999] pointer-events-none hidden">
    <div id="toast" class="pointer-events-auto flex max-w-sm items-start gap-3 rounded-2xl bg-slate-900 text-white px-4 py-3 shadow-lg ring-1 ring-white/10 opacity-0 translate-y-[-8px] transition duration-200">
      <div class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white text-sm font-bold" aria-hidden="true">✓</div>
      <div class="min-w-0 flex-1">
        <div id="toastMsg" class="text-sm font-medium leading-5">Добавлено</div>
        <div class="mt-2 flex flex-wrap gap-2">
          <a id="toastGoCart" href="{{ route('cart.index') }}" class="inline-flex items-center rounded-xl bg-white/10 px-3 py-1.5 text-xs font-semibold hover:bg-white/20">Перейти в корзину</a>
          <button id="toastClose" type="button" class="inline-flex items-center rounded-xl bg-white/0 px-3 py-1.5 text-xs font-semibold hover:bg-white/10">Закрыть</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function () {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const badge = document.getElementById('cartCountBadge');

      const wrap = document.getElementById('toastWrap');
      const toast = document.getElementById('toast');
      const msgEl = document.getElementById('toastMsg');
      const closeBtn = document.getElementById('toastClose');

      let toastTimer = null;

      function showToast(message) {
        if (!wrap || !toast || !msgEl) return;
        msgEl.textContent = message || 'Готово';
        wrap.classList.remove('hidden');
        // animate in
        requestAnimationFrame(() => {
          toast.classList.remove('opacity-0', 'translate-y-[-8px]');
          toast.classList.add('opacity-100', 'translate-y-0');
        });

        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(hideToast, 2400);
      }

      function hideToast() {
        if (!wrap || !toast) return;
        toast.classList.add('opacity-0', 'translate-y-[-8px]');
        toast.classList.remove('opacity-100', 'translate-y-0');
        setTimeout(() => wrap.classList.add('hidden'), 180);
      }

      closeBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        hideToast();
      });

      async function postJSON(url, data) {
        const res = await fetch(url, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data || {})
        });
        return res;
      }

      function formatRub(n) {
        try { return new Intl.NumberFormat('ru-RU').format(n) + ' ₽'; }
        catch(e){ return n + ' ₽'; }
      }

      function updateBadge(count) {
        if (badge && count !== undefined && count !== null) badge.textContent = String(count);
      }

      // ✅ Add-to-cart AJAX (works for any form posting to /cart/add/*)
      document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;

        const action = form.getAttribute('action') || '';
        const isAddToCart = form.hasAttribute('data-add-to-cart') || /\/cart\/add\/\d+/.test(action);

        if (!isAddToCart) return;

        e.preventDefault();

        const btn = form.querySelector('[data-add-to-cart-button], button[type="submit"], input[type="submit"]');
        const prevText = btn?.textContent;
        if (btn) { btn.disabled = true; btn.classList.add('opacity-70'); }

        try {
          const fd = new FormData(form);
          const res = await fetch(action, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'X-CSRF-TOKEN': csrf,
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            },
            body: fd
          });

          const data = await res.json().catch(() => null);

          if (res.ok && data && data.success) {
            updateBadge(data.cart_count);
            showToast(data.message || 'Добавлено в корзину');
          } else {
            showToast((data && (data.message || data.error)) || 'Не удалось добавить в корзину');
          }
        } catch (err) {
          showToast('Ошибка сети при добавлении');
        } finally {
          if (btn) { btn.disabled = false; btn.classList.remove('opacity-70'); if (prevText) btn.textContent = prevText; }
        }
      }, true);

      // ✅ Cart quantity +/- buttons (no reload)
      document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.cart-qty-btn');
        if (!btn) return;

        const id = btn.getAttribute('data-id');
        const delta = parseInt(btn.getAttribute('data-delta') || '0', 10);
        if (!id || !delta) return;

        e.preventDefault();

        const row = document.querySelector(`[data-cart-item-id="${id}"]`);
        const qtyEl = row?.querySelector(`[data-qty-id="${id}"]`);
        const price = parseFloat(row?.getAttribute('data-price') || '0');

        const currentQty = parseInt(qtyEl?.textContent || '1', 10) || 1;
        const nextQty = Math.max(1, currentQty + delta);

        // optimistic UI
        if (qtyEl) qtyEl.textContent = String(nextQty);

        try {
          const url = `/cart/update/${id}`;
          const res = await postJSON(url, { quantity: nextQty });

          const data = await res.json().catch(() => null);

          if (res.ok && data && data.success) {
            updateBadge(data.cart_count);
            // update line total
            const lineEl = row?.querySelector(`[data-item-total-id="${id}"]`);
            if (lineEl) {
              const lineTotal = (data.item_total !== undefined) ? data.item_total : (price * nextQty);
              lineEl.textContent = formatRub(lineTotal);
            }
            // update cart total if provided
            const cartTotalEl = document.getElementById('cartTotal');
            if (cartTotalEl) {
              if (data.total !== undefined) cartTotalEl.textContent = formatRub(data.total);
              else {
                // fallback: sum line totals
                const sum = Array.from(document.querySelectorAll('[data-item-total-id]'))
                  .map(el => parseFloat((el.textContent || '').replace(/[^\d.]/g,'') || '0'))
                  .reduce((a,b)=>a+b,0);
                cartTotalEl.textContent = formatRub(sum);
              }
            }
          } else {
            // rollback on error
            if (qtyEl) qtyEl.textContent = String(currentQty);
            showToast((data && (data.message || data.error)) || 'Не удалось обновить количество');
          }
        } catch (err) {
          if (qtyEl) qtyEl.textContent = String(currentQty);
          showToast('Ошибка сети при обновлении');
        }
      });

      // ✅ Remove item (AJAX)
      document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        const action = form.getAttribute('action') || '';
        const isRemove = form.hasAttribute('data-cart-remove') || /\/cart\/remove\/\d+/.test(action);
        const isClear = form.hasAttribute('data-cart-clear') || /\/cart\/clear/.test(action);

        if (!isRemove && !isClear) return;

        e.preventDefault();

        if (isRemove && !confirm('Удалить товар из корзины?')) return;
        if (isClear && !confirm('Очистить всю корзину?')) return;

        try {
          const fd = new FormData(form);
          const res = await fetch(action, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'X-CSRF-TOKEN': csrf,
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            },
            body: fd
          });
          const data = await res.json().catch(() => null);

          if (res.ok && data && data.success) {
            updateBadge(data.cart_count);

            if (isRemove) {
              const idMatch = action.match(/\/cart\/remove\/(\d+)/);
              const id = idMatch ? idMatch[1] : null;
              const row = id ? document.querySelector(`[data-cart-item-id="${id}"]`) : null;
              row?.remove();
            }

            if (isClear) {
              // simplest: reload to show empty state
              window.location.reload();
              return;
            }

            // update total
            const cartTotalEl = document.getElementById('cartTotal');
            if (cartTotalEl && data.total !== undefined) cartTotalEl.textContent = formatRub(data.total);

            showToast(data.message || (isRemove ? 'Удалено' : 'Готово'));
          } else {
            showToast((data && (data.message || data.error)) || 'Операция не выполнена');
          }
        } catch (err) {
          showToast('Ошибка сети');
        }
      }, true);
    })();
  </script>

</body>
</html>
