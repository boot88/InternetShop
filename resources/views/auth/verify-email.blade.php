@extends('layouts.app')
@section('title','Подтверждение email — '.config('store.name','TechZone'))
@section('robots','noindex,nofollow')
@section('content')
<section class="mx-auto max-w-md px-4 py-12 sm:px-6"><div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200 sm:p-8"><h1 class="text-2xl font-semibold text-slate-950">Подтвердите email</h1><p class="mt-3 text-sm leading-6 text-slate-600">Мы отправили ссылку на {{ auth()->user()->email }}. После подтверждения email будет отмечен в профиле.</p><form action="{{ route('verification.send') }}" method="POST" class="mt-6">@csrf<button class="w-full rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white">Отправить письмо повторно</button></form><a href="{{ route('profile.edit') }}" class="mt-5 block text-center text-sm font-semibold text-indigo-700">Перейти в профиль</a></div></section>
@endsection
