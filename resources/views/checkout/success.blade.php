@extends('layouts.app')

@section('title', 'Заказ принят — TechZone')

@section('content')
<section class="bg-slate-50 py-16 sm:py-24">
  <div class="mx-auto max-w-xl px-4 text-center sm:px-6">
    <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-emerald-100 text-3xl text-emerald-700">✓</div>
    <h1 class="mt-6 text-3xl font-semibold tracking-tight text-slate-950">Заказ принят</h1>
    <p class="mt-3 text-slate-600">Номер заказа: <span class="font-semibold text-slate-900">{{ $order->order_number }}</span>. Мы свяжемся с вами для подтверждения.</p>
    <p class="mt-2 text-lg font-semibold text-slate-950">{{ number_format($order->total, 0, ',', ' ') }} ₽</p>
    <a href="{{ route('products.index') }}" class="mt-8 inline-flex rounded-2xl bg-indigo-600 px-5 py-3 font-semibold text-white hover:bg-indigo-700">Вернуться в каталог</a>
  </div>
</section>
@endsection
