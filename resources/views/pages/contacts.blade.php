@extends('layouts.app')
@section('title', 'Контакты — '.config('store.name', 'TechZone'))
@section('meta_description', 'Контакты и форма обратной связи магазина.')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
  <p class="text-sm font-semibold text-indigo-600">СВЯЗЬ С МАГАЗИНОМ</p><h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Контакты</h1>
  <div class="mt-8 grid gap-6 lg:grid-cols-[.8fr_1.2fr]">
    <aside class="space-y-4 rounded-3xl bg-slate-900 p-6 text-white">
      @if($contacts['phone'])<div><p class="text-xs text-slate-400">Телефон</p><a href="tel:{{ preg_replace('/[^+0-9]/','',$contacts['phone']) }}" class="mt-1 block font-semibold">{{ $contacts['phone'] }}</a></div>@endif
      @if($contacts['email'])<div><p class="text-xs text-slate-400">Email</p><a href="mailto:{{ $contacts['email'] }}" class="mt-1 block font-semibold">{{ $contacts['email'] }}</a></div>@endif
      @if($contacts['address'])<div><p class="text-xs text-slate-400">Адрес</p><p class="mt-1 font-semibold">{{ $contacts['address'] }}</p></div>@endif
      @if($contacts['hours'])<div><p class="text-xs text-slate-400">Часы работы</p><p class="mt-1 font-semibold">{{ $contacts['hours'] }}</p></div>@endif
      @if(!$contacts['phone'] && !$contacts['email'] && !$contacts['address'])<p class="text-sm leading-6 text-slate-300">Контактные данные уточняются. Форма начнёт отправлять сообщения после настройки email магазина.</p>@endif
    </aside>
    <form action="{{ route('contact.submit') }}" method="POST" class="rounded-3xl bg-white p-6 ring-1 ring-slate-200">@csrf
      <h2 class="text-xl font-semibold text-slate-950">Написать нам</h2>
      @if($errors->any())<div class="mt-4 rounded-xl bg-rose-50 p-3 text-sm text-rose-800">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
      <div class="mt-5 grid gap-4 sm:grid-cols-2">
        <label class="text-sm font-medium">Имя<input name="name" value="{{ old('name') }}" required class="mt-1.5 w-full rounded-xl border-slate-300"></label>
        <label class="text-sm font-medium">Телефон<input name="phone" value="{{ old('phone') }}" required class="mt-1.5 w-full rounded-xl border-slate-300"></label>
        <label class="text-sm font-medium">Email<input type="email" name="email" value="{{ old('email') }}" required class="mt-1.5 w-full rounded-xl border-slate-300"></label>
        <label class="text-sm font-medium">Тема<input name="subject" value="{{ old('subject') }}" required class="mt-1.5 w-full rounded-xl border-slate-300"></label>
        <label class="text-sm font-medium sm:col-span-2">Сообщение<textarea name="message" rows="5" required class="mt-1.5 w-full rounded-xl border-slate-300">{{ old('message') }}</textarea></label>
      </div>
      <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
      <label class="mt-4 flex items-start gap-3 text-sm text-slate-600"><input type="checkbox" name="privacy_consent" value="1" required class="mt-1 rounded border-slate-300 text-indigo-600"><span>Согласен на обработку данных согласно <a href="{{ route('privacy') }}" class="font-semibold text-indigo-700">политике конфиденциальности</a>.</span></label>
      <button class="mt-5 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Отправить сообщение</button>
    </form>
  </div>
</section>
@endsection
