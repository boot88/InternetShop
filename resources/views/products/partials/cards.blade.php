@if($products->isNotEmpty())
  <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
    @foreach($products as $product)
      @php $img = $product->main_image; @endphp
      <article class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
        <a href="{{ route('products.show', $product->slug) }}" class="block">
          <div class="aspect-[4/3] bg-slate-100">
            @if($img)
              <img src="{{ $img->getUrl() }}" alt="{{ $img->alt_text ?? $product->name }}" class="h-full w-full object-cover" loading="lazy">
            @else
              <div class="grid h-full place-items-center text-sm text-slate-400">Нет фото</div>
            @endif
          </div>
          <div class="p-4 pb-3">
            <p class="truncate text-xs text-slate-500">{{ $product->brand?->name ?? $product->categories->first()?->name }}</p>
            <h3 class="mt-1 line-clamp-2 text-sm font-semibold text-slate-900 group-hover:text-indigo-600">{{ $product->name }}</h3>
          </div>
        </a>
        <div class="flex items-center justify-between gap-2 px-4 pb-4">
          <div><span class="text-base font-semibold text-slate-950">{{ number_format($product->final_price, 0, ',', ' ') }} ₽</span>@if($product->has_discount)<span class="ml-1 text-xs text-slate-400 line-through">{{ number_format($product->compare_price, 0, ',', ' ') }} ₽</span>@endif</div>
          @if($product->uses_variants)
            <a href="{{ route('products.show', $product->slug) }}" class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">Выбрать</a>
          @elseif($product->in_stock)
            <form method="POST" action="{{ route('cart.add', $product) }}" data-add-to-cart>@csrf<button type="submit" data-add-to-cart-button class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">В корзину</button></form>
          @else
            <span class="text-xs font-medium text-slate-400">Нет в наличии</span>
          @endif
        </div>
      </article>
    @endforeach
  </div>
@endif
