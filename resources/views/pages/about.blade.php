@extends('layouts.app')
@section('title', 'О магазине — '.config('store.name', 'TechZone'))
@section('meta_description', 'Информация о магазине, принципах работы и поддержке покупателей.')
@section('content')
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
  <p class="text-sm font-semibold text-indigo-600">О МАГАЗИНЕ</p>
  <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">{{ config('store.name', 'TechZone') }}</h1>
  <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Мы помогаем выбрать электронику и проверяем наличие перед подтверждением заказа. Цена товара фиксируется в заказе, а стоимость и срок доставки согласуются до оплаты.</p>
  <div class="mt-10 grid gap-5 sm:grid-cols-3">
    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><h2 class="font-semibold text-slate-950">Проверка наличия</h2><p class="mt-2 text-sm leading-6 text-slate-600">Остаток повторно проверяется перед созданием заказа.</p></div>
    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><h2 class="font-semibold text-slate-950">Понятные условия</h2><p class="mt-2 text-sm leading-6 text-slate-600">Доставка, оплата и возврат собраны в отдельных разделах сайта.</p></div>
    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><h2 class="font-semibold text-slate-950">Связь с магазином</h2><p class="mt-2 text-sm leading-6 text-slate-600">Задать вопрос можно через <a class="font-semibold text-indigo-700" href="{{ route('contacts') }}">форму контактов</a>.</p></div>
  </div>
  @if($store['legal_name'] || $store['address'])
    <div class="mt-10 rounded-2xl bg-slate-900 p-6 text-white"><h2 class="text-lg font-semibold">{{ $store['legal_name'] ?: config('store.name') }}</h2>@if($store['address'])<p class="mt-2 text-sm text-slate-300">{{ $store['address'] }}</p>@endif<a href="{{ route('requisites') }}" class="mt-4 inline-flex text-sm font-semibold text-indigo-200">Реквизиты продавца →</a></div>
  @endif
</section>
@endsection
