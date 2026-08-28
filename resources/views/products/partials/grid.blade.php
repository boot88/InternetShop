@if($products->isNotEmpty())
  <div class="grid grid-cols-1 gap-3 min-[430px]:grid-cols-2 sm:gap-4 xl:grid-cols-3">
    @foreach($products as $product)
      @php $img = $product->main_image; $src = $img?->getUrl() ?? asset('images/product-placeholder.svg'); @endphp
      <article class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
        <a href="{{ route('products.show', $product->slug) }}" class="block">
          <div class="relative aspect-square bg-slate-50 p-3 sm:p-4">
            <img src="{{ $src }}" alt="{{ $img?->alt_text ?? $product->name }}" class="h-full w-full object-contain" loading="lazy" decoding="async" width="800" height="800">
            <span class="absolute left-3 top-3 rounded-full px-2 py-1 text-[10px] font-semibold {{ $product->in_stock ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">{{ $product->in_stock ? 'В наличии' : 'Нет в наличии' }}</span>
          </div>
          <div class="px-3 pb-2 sm:px-4"><p class="truncate text-xs text-slate-500">{{ $product->brand?->name ?? $product->categories->first()?->name }}</p><h2 class="mt-1 line-clamp-2 min-h-10 text-sm font-semibold text-slate-900 group-hover:text-indigo-600">{{ $product->name }}</h2></div>
        </a>
        <div class="px-3 pb-3 sm:px-4 sm:pb-4">
          <div class="mb-3 flex items-center justify-between gap-2"><p class="text-xs text-slate-500">{{ $product->in_stock ? 'Доставка после подтверждения' : 'Сообщим о поступлении' }}</p><button type="button" data-quick-view data-name="{{ $product->name }}" data-brand="{{ $product->brand?->name }}" data-price="{{ number_format($product->final_price,0,',',' ') }} ₽" data-image="{{ $src }}" data-url="{{ route('products.show',$product->slug) }}" data-stock="{{ $product->in_stock?'В наличии':'Нет в наличии' }}" class="hidden text-xs font-semibold text-indigo-700 sm:inline">Быстро</button></div>
          <div class="flex items-center justify-between gap-2">
            <div class="min-w-0"><span class="text-base font-semibold text-slate-950">{{ number_format($product->final_price, 0, ',', ' ') }} ₽</span>@if($product->old_price)<span class="ml-1 hidden text-xs text-slate-400 line-through sm:inline">{{ number_format($product->old_price, 0, ',', ' ') }} ₽</span>@endif</div>
            @if($product->uses_variants)
              <a href="{{ route('products.show', $product->slug) }}" class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">Выбрать</a>
            @elseif($product->in_stock)
              <form method="POST" action="{{ route('cart.add', $product) }}" data-add-to-cart>@csrf<input type="hidden" name="quantity" value="1"><button type="submit" data-add-to-cart-button class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">В корзину</button></form>
            @else
              <a href="{{ route('products.show', $product->slug) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Подробнее</a>
            @endif
          </div>
        </div>
      </article>
    @endforeach
  </div>

  <div class="mt-8">{{ $products->links() }}</div>
@else
  <div class="rounded-2xl bg-white p-10 text-center ring-1 ring-slate-200"><p class="font-semibold text-slate-900">Ничего не найдено</p><p class="mt-2 text-sm text-slate-500">Попробуйте изменить фильтры или сбросить их.</p><a href="{{ route('products.index') }}" class="mt-4 inline-flex rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Сбросить фильтры</a></div>
@endif
