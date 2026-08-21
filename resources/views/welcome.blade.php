@extends('layouts.app')

@section('title', 'TechZone — техника без лишнего шума')
@section('meta_description', 'Интернет-магазин TechZone: каталог электроники, актуальные цены, проверка наличия и оформление заказа онлайн.')

@section('content')
<section class="overflow-hidden bg-slate-950 text-white">
  <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 sm:py-20 lg:grid-cols-[1.15fr_.85fr] lg:px-8">
    <div class="max-w-2xl">
      <p class="inline-flex rounded-full border border-indigo-300/30 bg-indigo-400/10 px-3 py-1 text-xs font-semibold tracking-[.16em] text-indigo-200">ТЕХНИКА ДЛЯ ЖИЗНИ И РАБОТЫ</p>
      <h1 class="mt-6 text-4xl font-semibold tracking-tight sm:text-5xl">Выбирайте технику спокойно.</h1>
      <p class="mt-5 max-w-xl text-lg leading-8 text-slate-300">Понятный каталог, актуальные цены и помощь менеджера до оформления заказа. Без навязчивых обещаний и скрытых условий.</p>
      <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ route('products.index') }}" class="rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">Перейти в каталог</a>
        <a href="{{ route('deals') }}" class="rounded-2xl border border-white/20 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Смотреть акции</a>
      </div>
      <dl class="mt-10 grid grid-cols-3 gap-4 border-t border-white/10 pt-6 text-sm">
        <div><dt class="text-slate-400">Ассортимент</dt><dd class="mt-1 text-lg font-semibold">{{ $categories->sum('products_count') }}+</dd></div>
        <div><dt class="text-slate-400">Поддержка</dt><dd class="mt-1 text-lg font-semibold">7 дней</dd></div>
        <div><dt class="text-slate-400">Оформление</dt><dd class="mt-1 text-lg font-semibold">Онлайн</dd></div>
      </dl>
    </div>
    <div class="relative min-h-72 rounded-[2rem] border border-white/10 bg-gradient-to-br from-indigo-500/40 via-slate-900 to-cyan-400/20 p-6 shadow-2xl">
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_20%,rgba(255,255,255,.24),transparent_35%)]"></div>
      <div class="relative flex h-full flex-col justify-between">
        <span class="w-fit rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white/80">Актуальный выбор</span>
        <div><p class="text-sm text-slate-200">Подберите технику по категории, бренду и бюджету.</p><a href="{{ route('products.index') }}" class="mt-4 inline-flex text-sm font-semibold text-white underline decoration-indigo-300 underline-offset-4">Открыть фильтры →</a></div>
      </div>
    </div>
  </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
  @if($searchQuery !== '')
    <div class="mb-12">
      <div class="flex items-end justify-between gap-4"><div><p class="text-sm font-semibold text-indigo-600">РЕЗУЛЬТАТ ПОИСКА</p><h2 class="mt-1 text-2xl font-semibold text-slate-950">«{{ $searchQuery }}»</h2></div><a href="{{ route('products.index', ['search' => $searchQuery]) }}" class="text-sm font-semibold text-indigo-600">Все результаты →</a></div>
      <div class="mt-6">@include('products.partials.cards', ['products' => $searchResults])</div>
      @if($searchResults->isEmpty())<p class="mt-5 rounded-2xl bg-slate-100 p-4 text-sm text-slate-600">По этому запросу ничего не найдено. Попробуйте название товара или категорию.</p>@endif
    </div>
  @endif

  <div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-sm font-semibold text-indigo-600">КАТАЛОГ</p><h2 class="mt-1 text-2xl font-semibold text-slate-950">Популярные категории</h2></div><a href="{{ route('products.index') }}" class="text-sm font-semibold text-indigo-600">Весь каталог →</a></div>
  <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
    @foreach($featuredCategories as $category)
      <a href="{{ route('products.index', ['category' => $category->id]) }}" class="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 transition hover:border-indigo-300 hover:shadow-sm"><span><span class="block font-semibold text-slate-950 group-hover:text-indigo-700">{{ $category->name }}</span><span class="mt-1 block text-sm text-slate-500">{{ $category->products_count }} товаров</span></span><span class="text-xl text-slate-400 group-hover:text-indigo-600">→</span></a>
    @endforeach
  </div>
</section>

<section class="border-y border-slate-200 bg-white py-12">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"><div class="flex items-end justify-between gap-4"><div><p class="text-sm font-semibold text-indigo-600">ВЫБОР ПОКУПАТЕЛЕЙ</p><h2 class="mt-1 text-2xl font-semibold text-slate-950">Популярное сейчас</h2></div><a href="{{ route('products.index') }}" class="text-sm font-semibold text-indigo-600">Перейти в каталог →</a></div><div class="mt-6">@include('products.partials.cards', ['products' => $popularProducts])</div></div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
  <div class="grid gap-12 lg:grid-cols-2">
    <div><div class="flex items-end justify-between gap-4"><div><p class="text-sm font-semibold text-indigo-600">НОВИНКИ</p><h2 class="mt-1 text-2xl font-semibold text-slate-950">Поступления</h2></div></div><div class="mt-6">@include('products.partials.cards', ['products' => $newProducts->take(4)])</div></div>
    <div><div class="flex items-end justify-between gap-4"><div><p class="text-sm font-semibold text-rose-600">ВЫГОДНЫЕ ЦЕНЫ</p><h2 class="mt-1 text-2xl font-semibold text-slate-950">Акции</h2></div><a href="{{ route('deals') }}" class="text-sm font-semibold text-indigo-600">Все акции →</a></div><div class="mt-6">@include('products.partials.cards', ['products' => $saleProducts->take(4)])</div></div>
  </div>
</section>

<section class="border-t border-slate-200 bg-slate-100 py-12"><div class="mx-auto grid max-w-7xl gap-5 px-4 sm:grid-cols-3 sm:px-6 lg:px-8"><div class="rounded-2xl bg-white p-5"><h2 class="font-semibold text-slate-950">Проверяем наличие</h2><p class="mt-2 text-sm leading-6 text-slate-600">Остаток проверяется при добавлении в корзину и перед заказом.</p></div><div class="rounded-2xl bg-white p-5"><h2 class="font-semibold text-slate-950">Честная цена</h2><p class="mt-2 text-sm leading-6 text-slate-600">Зачёркнутая цена — прежняя. В корзину попадает фактическая цена продажи.</p></div><div class="rounded-2xl bg-white p-5"><h2 class="font-semibold text-slate-950">Помощь до заказа</h2><p class="mt-2 text-sm leading-6 text-slate-600">Если нужен совет — оставьте сообщение, менеджер поможет с выбором.</p></div></div></section>
@endsection
