@extends('layouts.app')
@section('title', 'Восстановление пароля — '.config('store.name', 'TechZone'))
@section('robots', 'noindex,nofollow')
@section('content')
<section class="mx-auto max-w-md px-4 py-12 sm:px-6"><div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200 sm:p-8"><h1 class="text-2xl font-semibold text-slate-950">Восстановить пароль</h1><p class="mt-2 text-sm leading-6 text-slate-600">Введите email аккаунта. Мы отправим ссылку для создания нового пароля.</p>@if($errors->any())<div class="mt-4 rounded-xl bg-rose-50 p-3 text-sm text-rose-800">{{ $errors->first() }}</div>@endif<form action="{{ route('password.email') }}" method="POST" class="mt-6">@csrf<label class="block text-sm font-medium">Email<input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1.5 w-full rounded-xl border-slate-300"></label><button class="mt-5 w-full rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white">Отправить ссылку</button></form><a href="{{ route('login') }}" class="mt-5 block text-center text-sm font-semibold text-indigo-700">Вернуться ко входу</a></div></section>
@endsection
