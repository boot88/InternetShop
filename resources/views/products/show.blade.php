@extends('layouts.app')

@section('title', $product->name . ' — TechZone')
@section('meta_description', \Illuminate\Support\Str::limit($product->meta_description ?: $product->short_description ?: $product->description ?: ($product->name . ' — характеристики, наличие и условия покупки.'), 155))

@section('content')
<style>
  .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
</style>

<section class="py-8">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumbs -->
    <nav class="mb-6 text-sm text-slate-600">
      <ol class="flex flex-wrap items-center gap-2">
        <li><a href="{{ route('home') }}" class="hover:text-slate-900">Главная</a></li>
        <li class="text-slate-400">/</li>
        <li><a href="{{ route('products.index') }}" class="hover:text-slate-900">Каталог</a></li>
        <li class="text-slate-400">/</li>
        <li class="text-slate-900 line-clamp-2">{{ $product->name }}</li>
      </ol>
    </nav>

    <div class="grid gap-6 lg:grid-cols-12">
      <!-- Gallery -->
      <div class="lg:col-span-7">
        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
          @php
           $mainImg = null;

            $mainImg = $product->main_image?->getUrl();
         @endphp

          <div class="aspect-square overflow-hidden rounded-2xl bg-slate-50 p-4">
            @if($mainImg)
              <img src="{{ $mainImg }}" alt="{{ $product->name }}" class="h-full w-full object-contain" fetchpriority="high" width="1200" height="1200">
            @else
              <div class="h-full w-full grid place-items-center text-slate-500 text-sm">Нет фото</div>
            @endif
          </div>

          @if(isset($product->images) && method_exists($product->images, 'count') && $product->images->count() > 1)
            <div class="mt-4 grid grid-cols-4 gap-3">
              @foreach($product->images->take(8) as $img)
                @php $src = $img->getUrl(); @endphp
                <div class="aspect-square overflow-hidden rounded-2xl bg-slate-100">
                  @if($src)
                    <img src="{{ $src }}" alt="{{ $img->alt_text ?? $product->name }}" class="h-full w-full object-contain" loading="lazy" decoding="async">
                  @endif
                </div>
              @endforeach
            </div>
          @endif
        </div>

        <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
          <h2 class="text-base font-semibold text-slate-900">Описание</h2>
          <p class="mt-3 text-sm leading-6 text-slate-700">
            {{ $product->description ?? 'Описание скоро появится.' }}
          </p>
        </div>
      </div>

      <!-- Purchase box -->
      <aside class="lg:col-span-5">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
          <div class="flex items-start justify-between gap-4">
            <div>
              <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ $product->name }}</h1>
              <p class="mt-1 text-sm text-slate-600">Гарантия производителя • проверка перед отправкой</p>
            </div>
            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $product->in_stock ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
              {{ $product->in_stock ? 'В наличии' : 'Нет в наличии' }}
            </span>
          </div>

          <div class="mt-6 flex items-end justify-between">
            <div class="text-3xl font-semibold text-slate-900">
              {{ number_format($product->final_price, 0, ',', ' ') }} ₽
            </div>
            @if($product->compare_price && $product->compare_price > $product->price)
              <div class="text-sm text-slate-500 line-through">
                {{ number_format($product->compare_price, 0, ',', ' ') }} ₽
              </div>
            @endif
          </div>

          <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-6" data-add-to-cart>
            @csrf
            <input type="hidden" name="quantity" value="1">
            @if($product->uses_variants)
              <label class="block text-sm font-medium text-slate-800">
                Вариант
                <select name="variant_id" required class="mt-2 w-full rounded-xl border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">
                  <option value="" selected disabled>Выберите вариант</option>
                  @foreach($product->variants as $variant)
                    <option value="{{ $variant->id }}" @disabled(!$variant->stock_quantity)>
                      {{ $variant->attributeValues->pluck('value')->implode(' · ') ?: $variant->sku }} — {{ number_format($variant->final_price, 0, ',', ' ') }} ₽{{ $variant->stock_quantity ? '' : ' (нет в наличии)' }}
                    </option>
                  @endforeach
                </select>
              </label>
            @endif
            <button type="submit" data-add-to-cart-button @disabled(!$product->in_stock) class="mt-4 w-full rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:bg-slate-300">
              {{ $product->in_stock ? 'Добавить в корзину' : 'Нет в наличии' }}
            </button>
          </form>

          <div class="mt-4 grid grid-cols-1 gap-3 text-sm">
            <div class="rounded-2xl bg-slate-50 p-4">
              <div class="font-semibold text-slate-900">Наличие и доставка</div>
              <div class="mt-1 text-slate-600">{{ $product->in_stock ? 'На складе: ' . $product->stock_quantity . ' шт. Срок доставки подтвердим до оплаты.' : 'Сейчас нет на складе — оставьте запрос, чтобы узнать дату поставки.' }}</div>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
              <div class="font-semibold text-slate-900">Оплата</div>
              <div class="mt-1 text-slate-600">Способ оплаты и итоговую сумму менеджер подтверждает перед оформлением.</div>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
              <div class="font-semibold text-slate-900">Возврат</div>
              <div class="mt-1 text-slate-600"><a class="font-medium text-indigo-700 hover:text-indigo-800" href="{{ route('returns') }}">Условия возврата</a> зависят от категории и статуса товара — проверьте их до оплаты.</div>
            </div>
          </div>
        </div>
      </aside>
    </div>

    <section class="mt-6 grid gap-6 lg:grid-cols-12">
      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-7">
        <h2 class="text-base font-semibold text-slate-900">Характеристики</h2>
        <dl class="mt-4 divide-y divide-slate-100 text-sm">
          <div class="grid grid-cols-2 gap-4 py-3"><dt class="text-slate-500">Бренд</dt><dd class="font-medium text-slate-900">{{ $product->brand?->name ?? '—' }}</dd></div>
          <div class="grid grid-cols-2 gap-4 py-3"><dt class="text-slate-500">Артикул</dt><dd class="font-medium text-slate-900">{{ $product->sku ?: '—' }}</dd></div>
          <div class="grid grid-cols-2 gap-4 py-3"><dt class="text-slate-500">Категория</dt><dd class="font-medium text-slate-900">{{ $product->categories->pluck('name')->implode(', ') ?: '—' }}</dd></div>
        </dl>
      </div>
      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-5">
        <h2 class="text-base font-semibold text-slate-900">Отзывы</h2>
        @if($product->reviews->isNotEmpty())
          <p class="mt-3 text-sm text-slate-600">Средняя оценка: <span class="font-semibold text-slate-900">{{ number_format($product->reviews->avg('rating'), 1, ',', ' ') }}/5</span> · {{ $product->reviews->count() }} шт.</p>
          @foreach($product->reviews->take(2) as $review)
            <article class="mt-4 border-t border-slate-100 pt-4 text-sm"><p class="font-medium text-slate-900">{{ $review->user?->name ?? 'Покупатель' }} · {{ $review->rating }}/5</p><p class="mt-1 text-slate-600">{{ $review->comment }}</p></article>
          @endforeach
        @else
          <p class="mt-3 text-sm text-slate-600">Проверенных отзывов пока нет.</p>
        @endif
      </div>
    </section>
  </div>
</section>
@endsection
