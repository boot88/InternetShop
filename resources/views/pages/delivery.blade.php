@extends('layouts.app')
@section('title', 'Доставка и оплата — '.config('store.name', 'TechZone'))
@section('meta_description', 'Порядок согласования доставки и доступные способы оплаты заказа.')
@section('content')
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
  <p class="text-sm font-semibold text-indigo-600">ПОКУПАТЕЛЯМ</p><h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Доставка и оплата</h1>
  <div class="mt-8 grid gap-6 lg:grid-cols-2">
    <article class="rounded-3xl bg-white p-6 ring-1 ring-slate-200"><h2 class="text-xl font-semibold text-slate-950">Доставка</h2><p class="mt-3 leading-7 text-slate-600">{{ $store['delivery_note'] }}</p><ol class="mt-5 space-y-3 text-sm text-slate-600"><li><span class="font-semibold text-slate-900">1.</span> Укажите город и адрес при оформлении.</li><li><span class="font-semibold text-slate-900">2.</span> Менеджер проверит наличие и рассчитает доставку.</li><li><span class="font-semibold text-slate-900">3.</span> Срок и итоговая сумма подтверждаются до оплаты.</li></ol></article>
    <article class="rounded-3xl bg-white p-6 ring-1 ring-slate-200"><h2 class="text-xl font-semibold text-slate-950">Оплата</h2><p class="mt-3 leading-7 text-slate-600">В форме заказа доступны оплата при получении и запрос счёта для юридического лица или ИП. Возможность оплаты при получении зависит от выбранного способа доставки и подтверждается менеджером.</p><p class="mt-5 rounded-2xl bg-amber-50 p-4 text-sm text-amber-900">Не переводите деньги до получения подтверждения заказа и реквизитов от магазина.</p></article>
  </div>
  <div class="mt-6 rounded-3xl bg-slate-900 p-6 text-white"><h2 class="text-lg font-semibold">Получение заказа</h2><p class="mt-2 text-sm leading-6 text-slate-300">При получении проверьте внешний вид, комплектность и документы. Если обнаружено повреждение, зафиксируйте его до подписания документов перевозчика.</p></div>
</section>
@endsection
