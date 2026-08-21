@extends('layouts.app')

@section('title', 'Оформление заказа — TechZone')

@section('content')
<section class="bg-slate-50 py-8 sm:py-12">
  <div class="mx-auto grid max-w-6xl gap-6 px-4 lg:grid-cols-[1fr_360px] sm:px-6 lg:px-8">
    <form action="{{ route('checkout.store') }}" method="POST" class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-7">
      @csrf
      <h1 class="text-2xl font-semibold text-slate-950">Оформление заказа</h1>
      <p class="mt-2 text-sm text-slate-600">После заявки менеджер уточнит наличие, способ доставки и оплату.</p>

      @if($errors->any())
        <div class="mt-5 rounded-2xl bg-rose-50 p-4 text-sm text-rose-800" role="alert">
          <ul class="list-inside list-disc space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
          </ul>
        </div>
      @endif

      <div class="mt-7 grid gap-4 sm:grid-cols-2">
        <label class="block text-sm font-medium text-slate-800">
          Имя
          <input name="name" value="{{ old('name', auth()->user()?->name) }}" required autocomplete="name" class="mt-1.5 w-full rounded-xl border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">
        </label>
        <label class="block text-sm font-medium text-slate-800">
          Телефон
          <input name="phone" value="{{ old('phone') }}" required autocomplete="tel" inputmode="tel" class="mt-1.5 w-full rounded-xl border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">
        </label>
        <label class="block text-sm font-medium text-slate-800 sm:col-span-2">
          Email <span class="font-normal text-slate-500">(необязательно)</span>
          <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" autocomplete="email" class="mt-1.5 w-full rounded-xl border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">
        </label>
        <label class="block text-sm font-medium text-slate-800 sm:col-span-2">
          Адрес или город для доставки
          <textarea name="shipping_address" required rows="3" autocomplete="street-address" class="mt-1.5 w-full rounded-xl border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">{{ old('shipping_address') }}</textarea>
        </label>
      </div>

      <fieldset class="mt-6">
        <legend class="text-sm font-medium text-slate-800">Способ оплаты</legend>
        <div class="mt-2 grid gap-3 sm:grid-cols-2">
          <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 hover:border-indigo-300">
            <input class="mt-1 text-indigo-600" type="radio" name="payment_method" value="card_on_delivery" @checked(old('payment_method', 'card_on_delivery') === 'card_on_delivery')>
            <span><span class="block text-sm font-semibold">При получении</span><span class="mt-1 block text-xs text-slate-500">Согласуем детали с менеджером.</span></span>
          </label>
          <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 hover:border-indigo-300">
            <input class="mt-1 text-indigo-600" type="radio" name="payment_method" value="bank_transfer" @checked(old('payment_method') === 'bank_transfer')>
            <span><span class="block text-sm font-semibold">По счёту</span><span class="mt-1 block text-xs text-slate-500">Для юридических лиц и ИП.</span></span>
          </label>
        </div>
      </fieldset>

      <label class="mt-6 block text-sm font-medium text-slate-800">
        Комментарий к заказу <span class="font-normal text-slate-500">(необязательно)</span>
        <textarea name="customer_note" rows="3" class="mt-1.5 w-full rounded-xl border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">{{ old('customer_note') }}</textarea>
      </label>

      <button type="submit" class="mt-7 w-full rounded-2xl bg-indigo-600 px-5 py-3 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">Подтвердить заказ</button>
    </form>

    <aside class="h-fit rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-6">
      <h2 class="text-lg font-semibold text-slate-950">Ваш заказ</h2>
      <ul class="mt-5 divide-y divide-slate-100">
        @foreach($items as $item)
          <li class="py-3 first:pt-0">
            <div class="flex justify-between gap-4 text-sm font-medium text-slate-900"><span>{{ $item->product->name }}</span><span class="whitespace-nowrap">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽</span></div>
            <p class="mt-1 text-xs text-slate-500">{{ $item->variant_attributes ? $item->variant_attributes.' · ' : '' }}{{ $item->quantity }} шт.</p>
          </li>
        @endforeach
      </ul>
      <div class="mt-5 flex items-end justify-between border-t border-slate-200 pt-5"><span class="font-semibold text-slate-900">Итого</span><span class="text-2xl font-semibold text-slate-950">{{ number_format($total, 0, ',', ' ') }} ₽</span></div>
      <a href="{{ route('cart.index') }}" class="mt-5 block text-center text-sm font-medium text-indigo-600 hover:text-indigo-800">Вернуться в корзину</a>
    </aside>
  </div>
</section>
@endsection
