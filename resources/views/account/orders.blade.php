@extends('layouts.app')
@section('title', 'Мои заказы — '.config('store.name', 'TechZone'))
@section('robots', 'noindex,nofollow')
@section('content')
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8"><div class="flex items-end justify-between"><div><p class="text-sm font-semibold text-indigo-600">ЛИЧНЫЙ КАБИНЕТ</p><h1 class="mt-2 text-3xl font-semibold text-slate-950">Мои заказы</h1></div><a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-indigo-700">Профиль</a></div>
  <div class="mt-8 space-y-4">@forelse($orders as $order)<a href="{{ route('orders.show', $order) }}" class="grid gap-3 rounded-2xl bg-white p-5 ring-1 ring-slate-200 hover:ring-indigo-300 sm:grid-cols-[1fr_auto_auto] sm:items-center"><div><p class="font-semibold text-slate-950">{{ $order->order_number }}</p><p class="mt-1 text-sm text-slate-500">{{ $order->created_at->format('d.m.Y H:i') }} · {{ $order->items_count }} поз.</p></div><span class="text-sm font-medium text-slate-700">{{ $order->status_label }}</span><span class="font-semibold text-slate-950">{{ number_format($order->total,0,',',' ') }} ₽</span></a>@empty<div class="rounded-3xl bg-white p-10 text-center ring-1 ring-slate-200"><h2 class="font-semibold text-slate-950">Заказов пока нет</h2><a href="{{ route('products.index') }}" class="mt-4 inline-flex rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Открыть каталог</a></div>@endforelse</div>
  <div class="mt-8">{{ $orders->links() }}</div>
</section>
@endsection
