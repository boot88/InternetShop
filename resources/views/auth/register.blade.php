@extends('layouts.app')
@section('title', 'Регистрация — '.config('store.name', 'TechZone'))
@section('robots', 'noindex,nofollow')
@section('content')
<section class="mx-auto max-w-md px-4 py-12 sm:px-6">
  <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    <h1 class="text-2xl font-semibold text-slate-950">Создать аккаунт</h1><p class="mt-2 text-sm leading-6 text-slate-600">После регистрации мы отправим письмо со ссылкой подтверждения. Пароль в письме не передаётся; если вы его забудете, используйте безопасное восстановление доступа.</p>
    @if($errors->any())<div class="mt-4 rounded-xl bg-rose-50 p-3 text-sm text-rose-800">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    <form action="{{ route('register') }}" method="POST" class="mt-6 space-y-4">@csrf
      <label class="block text-sm font-medium">Имя<input name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-1.5 w-full rounded-xl border-slate-300"></label>
      <label class="block text-sm font-medium">Email<input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="mt-1.5 w-full rounded-xl border-slate-300"></label>
      <label class="block text-sm font-medium">Пароль<input type="password" name="password" required autocomplete="new-password" class="mt-1.5 w-full rounded-xl border-slate-300"><span class="mt-1 block text-xs text-slate-500">Не менее 8 символов, буквы и цифры.</span></label>
      <label class="block text-sm font-medium">Повторите пароль<input type="password" name="password_confirmation" required autocomplete="new-password" class="mt-1.5 w-full rounded-xl border-slate-300"></label>
      <label class="flex items-start gap-3 text-sm text-slate-600"><input type="checkbox" name="terms_consent" value="1" required class="mt-1 rounded border-slate-300 text-indigo-600"><span>Принимаю <a href="{{ route('terms') }}" class="font-semibold text-indigo-700">условия использования</a>.</span></label>
      <label class="flex items-start gap-3 text-sm text-slate-600"><input type="checkbox" name="privacy_consent" value="1" required class="mt-1 rounded border-slate-300 text-indigo-600"><span>Согласен на обработку данных по <a href="{{ route('privacy') }}" class="font-semibold text-indigo-700">политике конфиденциальности</a>.</span></label>
      <button class="w-full rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white">Зарегистрироваться</button>
    </form>
    <p class="mt-5 text-center text-sm text-slate-600">Уже есть аккаунт? <a href="{{ route('login') }}" class="font-semibold text-indigo-700">Войти</a></p>
  </div>
</section>
@endsection
