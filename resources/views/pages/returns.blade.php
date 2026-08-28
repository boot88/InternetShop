@extends('layouts.app')
@section('title', 'Возврат и гарантия — '.config('store.name', 'TechZone'))
@section('meta_description', 'Порядок обращения по возврату, обмену и гарантии.')
@section('content')
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
  <p class="text-sm font-semibold text-indigo-600">ПОКУПАТЕЛЯМ</p><h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Возврат и гарантия</h1>
  <p class="mt-4 max-w-3xl leading-7 text-slate-600">До передачи товара и в течение срока, предусмотренного законодательством для дистанционной продажи, покупатель может обратиться в магазин. Порядок зависит от состояния товара, причины обращения и применимых требований законодательства. До отправки товара свяжитесь с магазином и укажите номер заказа.</p>
  <div class="mt-8 grid gap-5 md:grid-cols-3">
    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><h2 class="font-semibold text-slate-950">Товар надлежащего качества</h2><p class="mt-2 text-sm leading-6 text-slate-600">Сохраните товарный вид, потребительские свойства, комплектность и документы. Срок и возможность возврата уточняются с учётом вида товара и закона.</p></div>
    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><h2 class="font-semibold text-slate-950">Недостаток или повреждение</h2><p class="mt-2 text-sm leading-6 text-slate-600">Опишите проблему и приложите фотографии или видео. Не пытайтесь самостоятельно ремонтировать устройство.</p></div>
    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><h2 class="font-semibold text-slate-950">Гарантия</h2><p class="mt-2 text-sm leading-6 text-slate-600">{{ $store['warranty_note'] }}</p></div>
  </div>
  <div class="mt-8 rounded-3xl bg-white p-6 ring-1 ring-slate-200"><h2 class="text-xl font-semibold text-slate-950">Как обратиться</h2><ol class="mt-4 space-y-3 text-sm leading-6 text-slate-600"><li><span class="font-semibold text-slate-900">1.</span> Подготовьте номер заказа, название товара и описание ситуации.</li><li><span class="font-semibold text-slate-900">2.</span> Напишите через <a href="{{ route('contacts') }}" class="font-semibold text-indigo-700">форму контактов</a> или используйте указанные контакты.</li><li><span class="font-semibold text-slate-900">3.</span> При недостатке приложите фото или видео. Не отправляйте товар и не выполняйте самостоятельный ремонт до согласования.</li><li><span class="font-semibold text-slate-900">4.</span> Дождитесь подтверждения адреса и способа передачи товара.</li></ol>@if($store['return_address'])<p class="mt-5 rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">Адрес для согласованного возврата: {{ $store['return_address'] }}</p>@endif</div>
  <p class="mt-6 text-xs leading-5 text-slate-500">Эта страница описывает рабочий порядок обращения и не ограничивает права покупателя, предусмотренные применимым законодательством.</p>
</section>
@endsection
