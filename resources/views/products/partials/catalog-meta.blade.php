@php
  $hasFilters = $selectedCategory || !empty($selectedBrands) || $inStockOnly || request()->has('price_min') || request()->has('price_max') || $search !== '';
  $baseParams = request()->except('page');
@endphp
<div id="catalogMeta" class="mt-5 flex flex-wrap items-center gap-2">
  <p class="mr-2 text-sm text-slate-500">Найдено: <span data-products-total class="font-semibold text-slate-900">{{ $products->total() }}</span></p>
  @if($search !== '')<a href="{{ route('products.index',array_diff_key($baseParams,['search'=>true,'q'=>true])) }}" class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">Поиск: {{ $search }} ×</a>@endif
  @if($inStockOnly)<a href="{{ route('products.index',array_diff_key($baseParams,['in_stock'=>true])) }}" class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">В наличии ×</a>@endif
  @if($selectedCategory)<a href="{{ route('products.index',array_diff_key($baseParams,['category'=>true])) }}" class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">{{ $categories->firstWhere('id',$selectedCategory)?->name }} ×</a>@endif
  @foreach($brands->whereIn('id',$selectedBrands) as $brand)
    @php $remainingBrands=array_values(array_diff($selectedBrands,[$brand->id])); $brandParams=$baseParams; if($remainingBrands){$brandParams['brands']=$remainingBrands;}else{unset($brandParams['brands']);} @endphp
    <a href="{{ route('products.index',$brandParams) }}" class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">{{ $brand->name }} ×</a>
  @endforeach
  @if(request()->has('price_min') || request()->has('price_max'))<a href="{{ route('products.index',array_diff_key($baseParams,['price_min'=>true,'price_max'=>true])) }}" class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">{{ number_format($priceMin,0,',',' ') }}–{{ number_format($priceMax,0,',',' ') }} ₽ ×</a>@endif
  @if($hasFilters)<a href="{{ route('products.index') }}" class="px-2 py-1 text-xs font-semibold text-slate-500 hover:text-slate-900">Сбросить всё</a>@endif
</div>
