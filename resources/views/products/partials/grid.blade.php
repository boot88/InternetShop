@if($products->count())
  <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
    @foreach($products as $product)
      @php
        $img = $product->images->sortByDesc('is_main')->sortBy('order')->first();
        $src = $img ? (method_exists($img, 'getUrl') ? $img->getUrl() : $img->image_path) : null;
      @endphp

      <div class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 hover:shadow-md transition">
        <a href="{{ route('products.show', $product->slug) }}" class="block">
          <div class="aspect-[4/3] bg-slate-50">
            @if($src)
              <img src="{{ $src }}"
                   alt="{{ $img->alt_text ?? $product->name }}"
                   class="h-full w-full object-cover"
                   loading="lazy">
            @else
              <div class="flex h-full w-full items-center justify-center text-slate-400 text-sm">Нет фото</div>
            @endif
          </div>

          <div class="p-4 pb-3">
            <div class="text-xs text-slate-500 truncate">
              {{ $product->brand->name ?? ($product->categories->first()->name ?? '') }}
            </div>

            <div class="mt-1 text-sm font-semibold line-clamp-2 group-hover:text-indigo-600">
              {{ $product->name }}
            </div>
          </div>
        </a>

        <div class="px-4 pb-4">
          <div class="flex items-center justify-between">
            <div class="text-lg font-semibold text-slate-900">
              {{ number_format($product->price, 0, ',', ' ') }} ₽
            </div>

            <form method="POST" action="{{ route('cart.add', $product->id) }}" data-add-to-cart>
              @csrf
              <button type="submit" data-add-to-cart-button
                      class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                В корзину
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-8">
    {{ $products->links() }}
  </div>
@else
  <div class="rounded-3xl bg-white p-10 text-center text-slate-500 ring-1 ring-slate-100">
    Ничего не найдено.
  </div>
@endif
