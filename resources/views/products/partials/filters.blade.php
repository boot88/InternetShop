@php
  $selCat = $selectedCategory ?? null;
  $selBrands = $selectedBrands ?? [];
@endphp

<form method="GET" action="{{ route('products.index') }}" class="space-y-3" data-filters-form>
  <input type="hidden" name="sort" value="{{ $sort ?? 'recommended' }}">

  <div class="flex items-center justify-between border-b border-slate-100 pb-4">
    <div><h2 class="font-semibold text-slate-900">Фильтры</h2><p class="mt-0.5 text-xs text-slate-500">Уточните выбор</p></div>
    @if(request()->query())<a data-reset-link href="{{ route('products.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Сбросить</a>@endif
  </div>

  <label class="flex cursor-pointer items-center justify-between gap-3 rounded-xl bg-slate-50 px-3 py-3 text-sm font-medium text-slate-700">
    <span>Только в наличии</span>
    <input type="checkbox" name="in_stock" value="1" @checked($inStockOnly ?? false) class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
  </label>

  <details open class="group border-b border-slate-100 py-3">
    <summary class="flex cursor-pointer list-none items-center justify-between text-sm font-semibold text-slate-900"><span>Категория</span><span class="text-slate-400 group-open:rotate-180">⌄</span></summary>
    <div class="mt-3 space-y-1">
      <label class="flex cursor-pointer items-center justify-between rounded-lg px-2 py-2 text-sm hover:bg-slate-50"><span><input type="radio" name="category" value="" @checked(!$selCat) class="mr-2 border-slate-300 text-indigo-600 focus:ring-indigo-500">Все товары</span></label>
      @foreach($categories as $cat)
        @php $cnt = (int)($categoryCounts[$cat->id] ?? 0); @endphp
        <label class="flex cursor-pointer items-center justify-between rounded-lg px-2 py-2 text-sm hover:bg-slate-50"><span class="truncate"><input type="radio" name="category" value="{{ $cat->id }}" @checked($selCat == $cat->id) class="mr-2 border-slate-300 text-indigo-600 focus:ring-indigo-500">{{ $cat->name }}</span><span class="ml-2 text-xs text-slate-400">{{ $cnt }}</span></label>
      @endforeach
    </div>
  </details>

  <details open class="group border-b border-slate-100 py-3">
    <summary class="flex cursor-pointer list-none items-center justify-between text-sm font-semibold text-slate-900"><span>Цена</span><span class="text-slate-400 group-open:rotate-180">⌄</span></summary>
    <div class="mt-4 rounded-2xl bg-slate-50 p-3">
      <div class="flex items-center justify-between text-xs font-semibold text-slate-600"><span data-out-min>{{ number_format($priceMin, 0, ',', ' ') }} ₽</span><span data-out-max>{{ number_format($priceMax, 0, ',', ' ') }} ₽</span></div>
      <div class="mt-3 space-y-2"><input type="range" min="{{ $rangeMin }}" max="{{ $rangeMax }}" value="{{ $priceMin }}" data-range-min class="w-full accent-indigo-600"><input type="range" min="{{ $rangeMin }}" max="{{ $rangeMax }}" value="{{ $priceMax }}" data-range-max class="w-full accent-indigo-600"></div>
      <div class="mt-3 grid grid-cols-2 gap-2"><input type="number" name="price_min" value="{{ $priceMin }}" data-input-min class="min-w-0 rounded-xl border-slate-200 bg-white px-3 py-2 text-sm" aria-label="Цена от"><input type="number" name="price_max" value="{{ $priceMax }}" data-input-max class="min-w-0 rounded-xl border-slate-200 bg-white px-3 py-2 text-sm" aria-label="Цена до"></div>
    </div>
  </details>

  <details @if(!empty($selBrands)) open @endif class="group border-b border-slate-100 py-3">
    <summary class="flex cursor-pointer list-none items-center justify-between text-sm font-semibold text-slate-900"><span>Бренды</span><span class="text-slate-400 group-open:rotate-180">⌄</span></summary>
    <div class="mt-3"><input type="search" data-brand-search class="w-full rounded-xl border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Найти бренд"></div>
    <div class="mt-2 space-y-1" data-brands-list>
      @foreach($brands as $brand)
        @php $cnt = (int)($brandCounts[$brand->id] ?? 0); $isSelected = in_array($brand->id, $selBrands); @endphp
        <label data-brand-row class="{{ $loop->index >= 6 && !$isSelected ? 'hidden' : '' }} flex cursor-pointer items-center justify-between rounded-lg px-2 py-2 text-sm hover:bg-slate-50"><span class="truncate"><input type="checkbox" name="brands[]" value="{{ $brand->id }}" @checked($isSelected) class="mr-2 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">{{ $brand->name }}</span><span class="ml-2 text-xs text-slate-400">{{ $cnt }}</span></label>
      @endforeach
    </div>
    @if($brands->count() > 6)<button type="button" data-toggle-brands class="mt-2 px-2 py-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800">Показать все бренды</button>@endif
  </details>

  <button type="submit" class="w-full rounded-xl bg-slate-900 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Показать товары</button>
</form>
