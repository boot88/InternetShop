@extends('layouts.app')
@section('title', 'Акции — '.config('store.name', 'TechZone'))
@section('meta_description', 'Товары со сниженной ценой из актуального каталога.')
@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
  <p class="text-sm font-semibold text-rose-600">АКТУАЛЬНЫЕ ЦЕНЫ</p>
  <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Товары со скидкой</h1>
  <p class="mt-3 max-w-2xl text-slate-600">Здесь отображаются только товары, для которых в каталоге указана прежняя и текущая цена.</p>
  <div class="mt-8">
    @if($products->isNotEmpty()) @include('products.partials.cards', ['products' => $products])
    @else <div class="rounded-3xl bg-white p-10 text-center ring-1 ring-slate-200"><h2 class="font-semibold text-slate-950">Активных скидок сейчас нет</h2><p class="mt-2 text-sm text-slate-600">Посмотрите полный каталог — цены и наличие указаны в карточках товаров.</p><a href="{{ route('products.index') }}" class="mt-5 inline-flex rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Открыть каталог</a></div>
    @endif
  </div>
  @if($products->hasPages())<div class="mt-8">{{ $products->links() }}</div>@endif
</section>
@endsection
