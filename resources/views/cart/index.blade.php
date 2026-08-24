@extends('layouts.app')
@section('title', 'Корзина — '.config('store.name', 'TechZone'))
@section('robots', 'noindex,nofollow')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
  <h1 class="text-3xl font-semibold tracking-tight text-slate-950">Корзина</h1>
  @if($cartItems->isNotEmpty())
    @if($priceChanges)<div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900"><p class="font-semibold">Цены обновились</p>@foreach($priceChanges as $change)<p class="mt-1">{{ $change['name'] }}: {{ number_format($change['old'],0,',',' ') }} ₽ → {{ number_format($change['new'],0,',',' ') }} ₽</p>@endforeach</div>@endif
    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_340px]">
      <div class="space-y-4">@foreach($cartItems as $item)
        <article class="flex flex-col gap-4 rounded-2xl bg-white p-4 ring-1 ring-slate-200 sm:flex-row sm:items-center" data-cart-item-id="{{ $item->id }}" data-price="{{ $item->price }}">
          <a href="{{ route('products.show',$item->product->slug) }}" class="h-24 w-24 shrink-0 rounded-xl bg-slate-50 p-2"><img src="{{ $item->product->main_image?->getUrl() ?? asset('images/product-placeholder.svg') }}" alt="{{ $item->product->name }}" class="h-full w-full object-contain"></a>
          <div class="min-w-0 flex-1"><a href="{{ route('products.show',$item->product->slug) }}" class="font-semibold text-slate-950 hover:text-indigo-700">{{ $item->product->name }}</a>@if($item->variant_attributes)<p class="mt-1 text-sm text-slate-500">{{ $item->variant_attributes }}</p>@endif<p class="mt-1 text-sm text-slate-500">{{ number_format($item->price,0,',',' ') }} ₽ за шт.</p>@php $available=$item->variant?->stock_quantity ?? $item->product->stock_quantity; @endphp @if($available<=5)<p class="mt-1 text-xs font-semibold text-amber-700">Осталось: {{ $available }} шт.</p>@endif</div>
          <div class="flex items-center gap-0"><button type="button" class="cart-qty-btn grid h-9 w-9 place-items-center rounded-l-xl border border-slate-300" data-id="{{ $item->id }}" data-delta="-1">−</button><span class="grid h-9 w-11 place-items-center border-y border-slate-300" data-qty-id="{{ $item->id }}">{{ $item->quantity }}</span><button type="button" class="cart-qty-btn grid h-9 w-9 place-items-center rounded-r-xl border border-slate-300" data-id="{{ $item->id }}" data-delta="1">+</button></div>
          <div class="flex items-center justify-between gap-3 sm:block sm:text-right"><p class="font-semibold text-slate-950" data-item-total-id="{{ $item->id }}">{{ number_format($item->price*$item->quantity,0,',',' ') }} ₽</p><form action="{{ route('cart.remove',$item->id) }}" method="POST" data-cart-remove class="sm:mt-2">@csrf @method('DELETE')<button class="text-sm font-semibold text-rose-700">Удалить</button></form></div>
        </article>
      @endforeach</div>
      <aside class="h-fit rounded-3xl bg-white p-6 ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Итого</h2><dl class="mt-5 space-y-3 text-sm"><div class="flex justify-between"><dt class="text-slate-500">Товары</dt><dd id="cartSubtotal">{{ number_format($subtotal,0,',',' ') }} ₽</dd></div><div class="flex justify-between text-emerald-700"><dt>Скидка</dt><dd id="cartDiscount">−{{ number_format($discount,0,',',' ') }} ₽</dd></div><div class="flex justify-between border-t pt-4 text-lg font-semibold"><dt>Без доставки</dt><dd id="cartTotal">{{ number_format($total,0,',',' ') }} ₽</dd></div></dl>
        @if($coupon)<div class="mt-5 flex items-center justify-between rounded-xl bg-emerald-50 p-3 text-sm text-emerald-800"><span>Промокод {{ $coupon->code }}</span><form action="{{ route('cart.coupon.remove') }}" method="POST">@csrf @method('DELETE')<button class="font-semibold">Удалить</button></form></div>@else<form action="{{ route('cart.coupon.apply') }}" method="POST" class="mt-5">@csrf<label class="text-sm font-medium">Промокод<div class="mt-1.5 flex"><input name="code" class="min-w-0 flex-1 rounded-l-xl border-slate-300 uppercase"><button class="rounded-r-xl bg-slate-900 px-4 text-sm font-semibold text-white">Применить</button></div></label>@error('coupon')<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror</form>@endif
        <p class="mt-5 text-xs leading-5 text-slate-500">Стоимость и срок доставки будут подтверждены до оплаты.</p><a href="{{ route('checkout.create') }}" class="mt-5 block rounded-xl bg-indigo-600 px-5 py-3 text-center font-semibold text-white">Оформить заказ</a><a href="{{ route('products.index') }}" class="mt-3 block text-center text-sm font-semibold text-indigo-700">Продолжить покупки</a><form action="{{ route('cart.clear') }}" method="POST" data-cart-clear class="mt-5 border-t pt-4 text-center">@csrf<button class="text-sm text-slate-500 hover:text-rose-700">Очистить корзину</button></form>
      </aside>
    </div>
  @else
    <div class="mt-8 rounded-3xl bg-white p-12 text-center ring-1 ring-slate-200"><div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-slate-100 text-2xl">🛒</div><h2 class="mt-5 text-xl font-semibold text-slate-950">Корзина пуста</h2><p class="mt-2 text-sm text-slate-600">Добавьте товары из каталога.</p><a href="{{ route('products.index') }}" class="mt-6 inline-flex rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white">Перейти в каталог</a></div>
  @endif
</section>
@endsection
