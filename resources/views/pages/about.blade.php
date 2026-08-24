@extends('layouts.app')

@section('title', 'О магазине — TechZone')
@section('meta_description', 'Информация о магазине TechZone, условиях заказа и поддержке покупателей.')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
  <p class="text-sm font-semibold text-indigo-600">О магазине</p>
  <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Техника с понятными условиями покупки</h1>
  <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">Перед оплатой подтверждаем наличие, комплектацию, срок и стоимость доставки. Информация в карточке товара должна совпадать с фактическими данными менеджера.</p>
  <div class="mt-8 grid gap-4 sm:grid-cols-3">
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><h2 class="font-semibold">Наличие</h2><p class="mt-2 text-sm text-slate-600">Не обещаем товар, если его нет на складе.</p></div>
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><h2 class="font-semibold">Поддержка</h2><p class="mt-2 text-sm text-slate-600">{{ $store['phone'] }}<br>{{ $store['hours'] }}</p></div>
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><h2 class="font-semibold">Реквизиты</h2><p class="mt-2 text-sm text-slate-600">{{ $store['legal_name'] }}</p></div>
  </div>
  <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm leading-6 text-amber-950">Перед запуском заполните юридическое наименование, ИНН/ОГРН, адрес, телефон, email и договорные условия доставки/возврата в `.env`. Не публикуйте демонстрационные контакты.</div>
</section>
@endsection
