@extends('layouts.app')
@section('title', 'Вход — '.config('store.name', 'TechZone'))
@section('robots', 'noindex,nofollow')
@section('content')
<section class="mx-auto max-w-md px-4 py-12 sm:px-6">
  <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    <h1 class="text-2xl font-semibold text-slate-950">Вход в {{ config('store.name', 'TechZone') }}</h1><p class="mt-2 text-sm text-slate-600">Заказы и контактные данные будут доступны в вашем аккаунте.</p>
    @if($errors->any())<div class="mt-4 rounded-xl bg-rose-50 p-3 text-sm text-rose-800">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    <form action="{{ route('login') }}" method="POST" class="mt-6 space-y-4">@csrf
      <label class="block text-sm font-medium">Email<input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="mt-1.5 w-full rounded-xl border-slate-300"></label>
      <label class="block text-sm font-medium">Пароль<input type="password" name="password" required autocomplete="current-password" class="mt-1.5 w-full rounded-xl border-slate-300"></label>
      <div class="flex items-center justify-between gap-3"><label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-indigo-600">Запомнить меня</label><a href="{{ route('password.request') }}" class="text-sm font-semibold text-indigo-700">Забыли пароль?</a></div>
      <button class="w-full rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white">Войти</button>
    </form>
    <p class="mt-5 text-center text-sm text-slate-600">Нет аккаунта? <a href="{{ route('register') }}" class="font-semibold text-indigo-700">Зарегистрироваться</a></p>
  </div>
</section>
@endsection
