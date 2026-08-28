@extends('layouts.app')
@section('title', 'Реквизиты продавца — '.config('store.name', 'TechZone'))
@section('content')
<section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
  <p class="text-sm font-semibold text-indigo-600">ИНФОРМАЦИЯ О ПРОДАВЦЕ</p><h1 class="mt-2 text-3xl font-semibold text-slate-950">Реквизиты</h1>
  <dl class="mt-8 divide-y divide-slate-100 rounded-3xl bg-white px-6 ring-1 ring-slate-200">
    @foreach([['Наименование',$store['legal_name']],['ИНН',$store['inn']],['ОГРН/ОГРНИП',$store['ogrn']],['Юридический адрес',$store['legal_address']],['Адрес магазина',$store['address']],['Email',$store['email']],['Телефон',$store['phone']],['Банковские реквизиты',$store['bank_details']]] as [$label,$value])
      <div class="grid gap-2 py-4 sm:grid-cols-[220px_1fr]"><dt class="text-sm text-slate-500">{{ $label }}</dt><dd class="font-medium text-slate-900">{{ $value ?: 'Не указано' }}</dd></div>
    @endforeach
  </dl>
</section>
@endsection
