@extends('layouts.app')

@section('title', $product->name . ' — TechZone')

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

            if (isset($product->images) && method_exists($product->images, 'isNotEmpty') && $product->images->isNotEmpty()) {
              $main = $product->images->sortByDesc('is_main')->sortBy('order')->first();

              // ВАЖНО: у ProductImage есть image_path и getUrl()
              $mainImg = method_exists($main, 'getUrl') ? $main->getUrl() : ($main->image_path ?? null);
            }
         @endphp

          <div class="aspect-[4/3] overflow-hidden rounded-2xl bg-slate-100">
            @if($mainImg)
              <img src="{{ $mainImg }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
            @else
              <div class="h-full w-full grid place-items-center text-slate-500 text-sm">Нет фото</div>
            @endif
          </div>

          @if(isset($product->images) && method_exists($product->images, 'count') && $product->images->count() > 1)
            <div class="mt-4 grid grid-cols-4 gap-3">
              @foreach($product->images->take(8) as $img)
                @php $src = $img->url ?? $img->path ?? null; @endphp
                <div class="aspect-square overflow-hidden rounded-2xl bg-slate-100">
                  @if($src)
                    <img src="{{ $src }}" alt="" class="h-full w-full object-cover">
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
              <p class="mt-1 text-sm text-slate-600">Официальная гарантия • Быстрая доставка</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
              В наличии
            </span>
          </div>

          <div class="mt-6 flex items-end justify-between">
            <div class="text-3xl font-semibold text-slate-900">
              {{ number_format($product->price, 0, ',', ' ') }} ₽
            </div>
            @if(!empty($product->old_price) && $product->old_price > $product->price)
              <div class="text-sm text-slate-500 line-through">
                {{ number_format($product->old_price, 0, ',', ' ') }} ₽
              </div>
            @endif
          </div>

          <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-6" data-add-to-cart>
            @csrf
            <button type="submit" data-add-to-cart-button class="w-full rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
              Добавить в корзину
            </button>
          </form>

          <div class="mt-4 grid grid-cols-1 gap-3 text-sm">
            <div class="rounded-2xl bg-slate-50 p-4">
              <div class="font-semibold text-slate-900">Доставка</div>
              <div class="mt-1 text-slate-600">По городу и в регионы. Сроки уточним при оформлении.</div>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
              <div class="font-semibold text-slate-900">Оплата</div>
              <div class="mt-1 text-slate-600">Картой онлайн, при получении или по счёту.</div>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
              <div class="font-semibold text-slate-900">Возврат</div>
              <div class="mt-1 text-slate-600">14 дней при сохранении товарного вида.</div>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>
@endsection
