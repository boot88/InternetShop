@extends('layouts.app')
@section('title', 'Доставка и оплата — '.config('store.name', 'TechZone'))
@section('meta_description', 'Порядок согласования доставки и доступные способы оплаты заказа.')
@section('content')
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
  <p class="text-sm font-semibold text-indigo-600">ПОКУПАТЕЛЯМ</p><h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Доставка и оплата</h1>
  <div class="mt-8 grid gap-6 lg:grid-cols-2">
    <article class="rounded-3xl bg-white p-6 ring-1 ring-slate-200"><h2 class="text-xl font-semibold text-slate-950">Самовывоз</h2><p class="mt-3 leading-7 text-slate-600">{{ $store['delivery_note'] }}</p><ul class="mt-5 space-y-3 text-sm leading-6 text-slate-600"><li><span class="font-semibold text-slate-900">e2e4:</span> выберите самовывоз при оформлении. Доставка до пункта бесплатна; готовность заказа подтвердим по SMS или email.</li><li><span class="font-semibold text-slate-900">СДЭК:</span> выберите удобный пункт. Тариф и срок подтверждаются менеджером до оплаты.</li><li><span class="font-semibold text-slate-900">Почта России:</span> доступны отделение или курьер, если способ доступен по указанному адресу. Тариф и срок подтверждаются до оплаты.</li></ul></article>
    <article class="rounded-3xl bg-white p-6 ring-1 ring-slate-200"><h2 class="text-xl font-semibold text-slate-950">Оплата</h2><p class="mt-3 leading-7 text-slate-600">Для физических лиц требуется 100% предоплата после подтверждения заказа. Для юридических лиц условия оплаты определяются счётом или договором. Онлайн-оплата на сайте пока не подключена: менеджер направит подтверждение и реквизиты.</p><p class="mt-5 rounded-2xl bg-amber-50 p-4 text-sm text-amber-900">Не переводите деньги до получения подтверждения заказа и реквизитов от TechZone.</p></article>
  </div>
  <div class="mt-6 rounded-3xl bg-slate-900 p-6 text-white"><h2 class="text-lg font-semibold">Получение заказа</h2><p class="mt-2 text-sm leading-6 text-slate-300">Для выдачи в СДЭК и Почте России понадобится документ, удостоверяющий личность. При получении проверьте внешний вид, комплектность и документы до подписания документов перевозчика. О готовности сообщим по SMS или email.</p></div>
</section>
@endsection
