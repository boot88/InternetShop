@extends('layouts.app')
@section('title', 'Профиль — '.config('store.name', 'TechZone'))
@section('robots', 'noindex,nofollow')
@section('content')
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
  <div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-sm font-semibold text-indigo-600">ЛИЧНЫЙ КАБИНЕТ</p><h1 class="mt-2 text-3xl font-semibold text-slate-950">Профиль</h1></div><a href="{{ route('orders.index') }}" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Мои заказы</a></div>
  <form action="{{ route('profile.update') }}" method="POST" class="mt-8 max-w-2xl rounded-3xl bg-white p-6 ring-1 ring-slate-200">@csrf @method('PATCH')
    @if($errors->any())<div class="mb-5 rounded-xl bg-rose-50 p-3 text-sm text-rose-800">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    <div class="grid gap-4 sm:grid-cols-2"><label class="text-sm font-medium">Имя<input name="name" value="{{ old('name', $user->name) }}" required class="mt-1.5 w-full rounded-xl border-slate-300"></label><label class="text-sm font-medium">Email<input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1.5 w-full rounded-xl border-slate-300"><span class="mt-1 block text-xs {{ $user->hasVerifiedEmail()?'text-emerald-700':'text-amber-700' }}">{{ $user->hasVerifiedEmail()?'Подтверждён':'Не подтверждён' }}</span></label><label class="text-sm font-medium">Телефон<input name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1.5 w-full rounded-xl border-slate-300"></label><label class="text-sm font-medium sm:col-span-2">Адрес<textarea name="address" rows="3" class="mt-1.5 w-full rounded-xl border-slate-300">{{ old('address', $user->address) }}</textarea></label></div>
    <button class="mt-5 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white">Сохранить</button>
  </form>
</section>
@endsection
