@php
  $selCat = $selectedCategory ?? null;
  $selBrands = $selectedBrands ?? [];
@endphp

<form method="GET" action="{{ route('products.index') }}" class="space-y-6" data-filters-form>

  {{-- Категории --}}
  <div>
    <div class="flex items-center justify-between">
      <h3 class="font-semibold">Категории</h3>
      @if(request()->query())
        <a data-reset-link href="{{ route('products.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Сброс</a>
      @endif
    </div>

    <div class="mt-3 space-y-2 text-sm">
      <label class="flex items-center justify-between gap-3">
        <span class="flex items-center gap-2">
          <input data-filter-input type="radio" name="category" value="" @checked(!$selCat)>
          <span>Все</span>
        </span>
        <span class="text-xs text-slate-400"></span>
      </label>

      @foreach($categories as $cat)
        @php $cnt = (int)($categoryCounts[$cat->id] ?? 0); @endphp
        <label class="flex items-center justify-between gap-3">
          <span class="flex items-center gap-2">
            <input data-filter-input type="radio" name="category" value="{{ $cat->id }}" @checked($selCat == $cat->id)>
            <span>{{ $cat->name }}</span>
          </span>
          <span class="text-xs text-slate-400">{{ $cnt }}</span>
        </label>
      @endforeach
    </div>
  </div>

  {{-- Цена --}}
  <div>
    <h3 class="font-semibold">Цена</h3>

    <div class="mt-3 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-100">
      <div class="flex items-center justify-between text-xs font-semibold text-slate-600">
        <span data-out-min>{{ number_format($priceMin ?? $rangeMin, 0, ',', ' ') }} ₽</span>
        <span data-out-max>{{ number_format($priceMax ?? $rangeMax, 0, ',', ' ') }} ₽</span>
      </div>

      <div class="mt-3 space-y-2">
        <input data-filter-input type="range" min="{{ $rangeMin }}" max="{{ $rangeMax }}"
               value="{{ $priceMin ?? $rangeMin }}" data-range-min class="w-full">
        <input data-filter-input type="range" min="{{ $rangeMin }}" max="{{ $rangeMax }}"
               value="{{ $priceMax ?? $rangeMax }}" data-range-max class="w-full">
      </div>

      <div class="mt-3 flex gap-2">
        <input data-filter-input type="number" name="price_min" value="{{ $priceMin ?? $rangeMin }}" data-input-min
               class="w-1/2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" placeholder="от">
        <input data-filter-input type="number" name="price_max" value="{{ $priceMax ?? $rangeMax }}" data-input-max
               class="w-1/2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" placeholder="до">
      </div>
    </div>
  </div>

  {{-- Бренды --}}
  <div>
    <h3 class="font-semibold">Бренды</h3>

    <div class="mt-3 space-y-2 text-sm">
      @foreach($brands as $brand)
        @php $cnt = (int)($brandCounts[$brand->id] ?? 0); @endphp
        <label class="flex items-center justify-between gap-3">
          <span class="flex items-center gap-2">
            <input data-filter-input type="checkbox" name="brands[]" value="{{ $brand->id }}" @checked(in_array($brand->id, $selBrands))>
            <span>{{ $brand->name }}</span>
          </span>
          <span class="text-xs text-slate-400">{{ $cnt }}</span>
        </label>
      @endforeach
    </div>
  </div>

  {{-- Кнопка нужна в основном на мобилке --}}
  <div class="pt-2 lg:hidden">
    <button type="submit" class="w-full rounded-2xl bg-slate-900 py-3 text-sm font-semibold text-white hover:bg-slate-800">
      Применить
    </button>
  </div>

</form>
