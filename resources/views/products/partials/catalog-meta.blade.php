@php
  $hasFilters = $selectedCategory || !empty($selectedBrands) || $inStockOnly || request()->has('price_min') || request()->has('price_max') || $search !== '';
@endphp

<div id="catalogMeta" class="mt-5 flex flex-wrap items-center gap-2">
  <p class="mr-2 text-sm text-slate-500">Найдено: <span data-products-total class="font-semibold text-slate-900">{{ $products->total() }}</span> товаров</p>
  @if($inStockOnly)<span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">В наличии</span>@endif
  @if($selectedCategory)<span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">{{ $categories->firstWhere('id', $selectedCategory)?->name }}</span>@endif
  @foreach($brands->whereIn('id', $selectedBrands) as $brand)<span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">{{ $brand->name }}</span>@endforeach
  @if(request()->has('price_min') || request()->has('price_max'))<span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">{{ number_format($priceMin, 0, ',', ' ') }}–{{ number_format($priceMax, 0, ',', ' ') }} ₽</span>@endif
  @if($hasFilters)<a href="{{ route('products.index') }}" class="px-2 py-1 text-xs font-semibold text-slate-500 hover:text-slate-900">Сбросить всё</a>@endif
</div>
