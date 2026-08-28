<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="app-base" content="{{ rtrim(url('/'), '/') }}">
  <title>@yield('title', config('store.name', 'TechZone'))</title>
  <meta name="description" content="@yield('meta_description', 'Каталог электроники с проверкой наличия перед оформлением заказа.')">
  <meta name="robots" content="@yield('robots', 'index,follow')">
  <link rel="canonical" href="{{ url()->current() }}">
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:site_name" content="{{ config('store.name', 'TechZone') }}">
  <meta property="og:title" content="@yield('title', config('store.name', 'TechZone'))">
  <meta property="og:description" content="@yield('meta_description', 'Каталог электроники с проверкой наличия перед оформлением заказа.')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="@yield('og_image', asset('favicon.svg'))">
  <meta name="twitter:card" content="summary_large_image">
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
  <link rel="preconnect" href="https://cdn.tailwindcss.com">
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>[x-cloak]{display:none!important}.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}.legal-content h1{font-size:1.875rem;line-height:2.25rem;font-weight:600;color:#020617}.legal-content h2{margin-top:2rem;font-size:1.25rem;line-height:1.75rem;font-weight:600;color:#0f172a}.legal-content p{margin-top:.75rem;line-height:1.75;color:#475569}.legal-content a{font-weight:600;color:#4338ca}</style>
  @php
    $organization = array_filter([
      '@context' => 'https://schema.org',
      '@type' => 'Organization',
      'name' => config('store.name', 'TechZone'),
      'url' => url('/'),
      'telephone' => config('store.phone'),
      'email' => config('store.email'),
      'address' => config('store.address'),
    ]);
  @endphp
  <script type="application/ld+json">@json($organization, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)</script>
  @stack('head')
</head>
<body class="flex min-h-screen flex-col overflow-x-hidden bg-slate-50 text-slate-800">
  <header class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/90 backdrop-blur" x-data="{ open:false, account:false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between gap-3">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2">
          <span class="grid h-9 w-9 place-items-center rounded-xl bg-indigo-600 font-black text-white">T</span>
          <span class="font-semibold tracking-tight text-slate-900">{{ config('store.name', 'TechZone') }}</span>
        </a>

        <form action="{{ route('products.search') }}" method="GET" class="hidden w-full max-w-xl md:block" data-search-form>
          <div class="relative">
            <input name="q" value="{{ request('search', request('q')) }}" placeholder="Поиск по названию, бренду или артикулу…" autocomplete="off" data-search-input class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 pr-20 text-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/30">
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-xl px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Найти</button>
            <div data-search-suggestions class="absolute inset-x-0 top-full z-50 mt-2 hidden overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xl"></div>
          </div>
        </form>

        <nav class="flex items-center gap-1">
          @if(config('store.phone'))
            <a href="tel:{{ preg_replace('/[^+0-9]/', '', config('store.phone')) }}" class="hidden rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 xl:inline-flex">{{ config('store.phone') }}</a>
          @endif
          <a href="{{ route('deals') }}" class="hidden rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 sm:inline-flex">Акции</a>
          <a href="{{ route('products.index') }}" class="hidden rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 sm:inline-flex">Каталог</a>
          <a href="{{ route('cart.index') }}" class="relative inline-flex items-center rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Корзина
            <span id="cartCountBadge" class="ml-2 inline-flex items-center justify-center rounded-full bg-indigo-600 px-2 py-0.5 text-xs font-semibold text-white">{{ $cartCount ?? 0 }}</span>
          </a>

          <div class="relative hidden md:block">
            @auth
              <button type="button" @click="account=!account" @click.outside="account=false" class="rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 16) }}</button>
              <div x-cloak x-show="account" class="absolute right-0 mt-2 w-52 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2 text-sm hover:bg-slate-50">Профиль</a>
                <a href="{{ route('orders.index') }}" class="block rounded-xl px-3 py-2 text-sm hover:bg-slate-50">Мои заказы</a>
                @if(auth()->user()->isAdmin())<a href="{{ route('admin.dashboard') }}" class="block rounded-xl px-3 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">Управление</a>@endif
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="w-full rounded-xl px-3 py-2 text-left text-sm text-rose-700 hover:bg-rose-50">Выйти</button></form>
              </div>
            @else
              <a href="{{ route('login') }}" class="inline-flex rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Войти</a>
            @endauth
          </div>

          <button type="button" class="inline-flex items-center justify-center rounded-xl p-2 hover:bg-slate-100 md:hidden" @click="open=!open" :aria-expanded="open" aria-label="Открыть меню">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
        </nav>
      </div>

      <div class="pb-4 md:hidden" x-cloak x-show="open" @click.outside="open=false">
        <form action="{{ route('products.search') }}" method="GET" class="mt-2" data-search-form>
          <div class="relative">
            <input name="q" value="{{ request('search', request('q')) }}" placeholder="Поиск товаров…" autocomplete="off" data-search-input class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/30">
            <div data-search-suggestions class="absolute inset-x-0 top-full z-50 mt-2 hidden overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xl"></div>
          </div>
        </form>
        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
          <a href="{{ route('products.index') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2">Каталог</a>
          <a href="{{ route('deals') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2">Акции</a>
          <a href="{{ route('delivery') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2">Доставка</a>
          <a href="{{ route('returns') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2">Возврат</a>
          @auth
            <a href="{{ route('profile.edit') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2">Профиль</a>
            <a href="{{ route('orders.index') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2">Мои заказы</a>
            @if(auth()->user()->isAdmin())<a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-indigo-700">Управление</a>@endif
            <form action="{{ route('logout') }}" method="POST">@csrf<button class="w-full rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-left text-rose-700">Выйти</button></form>
          @else
            <a href="{{ route('login') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2">Войти</a>
            <a href="{{ route('register') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-white">Регистрация</a>
          @endauth
        </div>
      </div>
    </div>
  </header>

  @if(session('success') || session('info') || $errors->any())
    <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
      @if(session('success'))<div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('success') }}</div>@endif
      @if(session('info'))<div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800" role="status">{{ session('info') }}</div>@endif
      @if($errors->has('cart') || $errors->has('contact'))<div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">{{ $errors->first('cart') ?: $errors->first('contact') }}</div>@endif
    </div>
  @endif

  <main class="flex-1">@yield('content')</main>

  <footer class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
      <div class="grid gap-8 md:grid-cols-4">
        <div><div class="font-semibold text-slate-900">{{ config('store.name', 'TechZone') }}</div><p class="mt-2 text-sm leading-6 text-slate-600">Каталог электроники с проверкой наличия перед заказом.</p></div>
        <div class="space-y-2 text-sm"><div class="font-semibold text-slate-900">Покупателям</div><a class="block text-slate-600 hover:text-slate-900" href="{{ route('delivery') }}">Доставка и оплата</a><a class="block text-slate-600 hover:text-slate-900" href="{{ route('returns') }}">Возврат</a><a class="block text-slate-600 hover:text-slate-900" href="{{ route('faq') }}">FAQ</a></div>
        <div class="space-y-2 text-sm"><div class="font-semibold text-slate-900">Магазин</div><a class="block text-slate-600 hover:text-slate-900" href="{{ route('about') }}">О магазине</a><a class="block text-slate-600 hover:text-slate-900" href="{{ route('contacts') }}">Контакты</a><a class="block text-slate-600 hover:text-slate-900" href="{{ route('requisites') }}">Реквизиты</a></div>
        <div class="space-y-2 text-sm"><div class="font-semibold text-slate-900">Документы</div><a class="block text-slate-600 hover:text-slate-900" href="{{ route('privacy') }}">Политика конфиденциальности</a><a class="block text-slate-600 hover:text-slate-900" href="{{ route('terms') }}">Условия использования</a>@if(config('store.email'))<a class="block text-slate-600 hover:text-slate-900" href="mailto:{{ config('store.email') }}">{{ config('store.email') }}</a>@endif</div>
      </div>
      <div class="mt-10 text-xs text-slate-500">© {{ date('Y') }} {{ config('store.name', 'TechZone') }}</div>
    </div>
  </footer>

  <div id="toastWrap" class="pointer-events-none fixed right-4 top-4 z-[9999] hidden">
    <div id="toast" class="pointer-events-auto flex max-w-sm items-start gap-3 rounded-2xl bg-slate-900 px-4 py-3 text-white opacity-0 shadow-lg transition">
      <div id="toastIcon" class="mt-0.5 grid h-6 w-6 place-items-center rounded-full bg-emerald-500 text-sm font-bold">✓</div>
      <div class="min-w-0 flex-1"><div id="toastMsg" class="text-sm font-medium"></div><div class="mt-2 flex gap-2"><a href="{{ route('cart.index') }}" class="rounded-xl bg-white/10 px-3 py-1.5 text-xs font-semibold hover:bg-white/20">В корзину</a><button id="toastClose" type="button" class="rounded-xl px-3 py-1.5 text-xs font-semibold hover:bg-white/10">Закрыть</button></div></div>
    </div>
  </div>

  @if(config('store.analytics_id'))
    <div id="cookieBanner" class="fixed inset-x-4 bottom-4 z-[80] mx-auto hidden max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl">
      <p class="text-sm text-slate-700">Сайт использует аналитические cookie только с вашего согласия. Подробнее — в <a href="{{ route('privacy') }}" class="font-semibold text-indigo-700">политике конфиденциальности</a>.</p>
      <div class="mt-3 flex gap-2"><button data-cookie-accept class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Разрешить</button><button data-cookie-reject class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold">Отказаться</button></div>
    </div>
  @endif

  <script>
    (() => {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const appBase = document.querySelector('meta[name="app-base"]')?.content || '';
      const badge = document.getElementById('cartCountBadge');
      const wrap = document.getElementById('toastWrap');
      const toast = document.getElementById('toast');
      const toastMsg = document.getElementById('toastMsg');
      const toastIcon = document.getElementById('toastIcon');
      let toastTimer;

      function formatRub(value) { return new Intl.NumberFormat('ru-RU').format(Number(value || 0)) + ' ₽'; }
      function updateBadge(value) { if (badge && value !== undefined) badge.textContent = String(value); }
      function showToast(message, type = 'success') {
        if (!wrap || !toast || !toastMsg) return;
        toastMsg.textContent = message || 'Готово';
        toastIcon.textContent = type === 'error' ? '!' : '✓';
        toastIcon.className = 'mt-0.5 grid h-6 w-6 place-items-center rounded-full text-sm font-bold ' + (type === 'error' ? 'bg-rose-500' : 'bg-emerald-500');
        wrap.classList.remove('hidden');
        requestAnimationFrame(() => toast.classList.remove('opacity-0'));
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => { toast.classList.add('opacity-0'); setTimeout(() => wrap.classList.add('hidden'), 180); }, 3000);
      }
      document.getElementById('toastClose')?.addEventListener('click', () => wrap.classList.add('hidden'));

      document.addEventListener('submit', async (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) return;
        const action = form.action || '';

        if (form.matches('[data-cart-clear]') && !confirm('Очистить всю корзину?')) { event.preventDefault(); return; }

        if (form.hasAttribute('data-add-to-cart')) {
          event.preventDefault();
          const button = form.querySelector('[data-add-to-cart-button], button[type="submit"]');
          if (button) button.disabled = true;
          try {
            const response = await fetch(action, { method:'POST', credentials:'same-origin', headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}, body:new FormData(form) });
            const data = await response.json().catch(() => ({}));
            if (!response.ok || !data.success) throw new Error(data.message || 'Не удалось добавить товар');
            updateBadge(data.cart_count); showToast(data.message);
          } catch (error) { showToast(error.message || 'Ошибка сети', 'error'); }
          finally { if (button) button.disabled = false; }
          return;
        }

        if (form.hasAttribute('data-cart-remove') || form.hasAttribute('data-cart-clear')) {
          event.preventDefault();
          try {
            const response = await fetch(action, { method:'POST', credentials:'same-origin', headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}, body:new FormData(form) });
            const data = await response.json().catch(() => ({}));
            if (!response.ok || !data.success) throw new Error(data.message || 'Операция не выполнена');
            if (data.empty || form.hasAttribute('data-cart-clear')) { window.location.reload(); return; }
            const id = action.match(/\/cart\/remove\/(\d+)/)?.[1];
            if (id) document.querySelector(`[data-cart-item-id="${id}"]`)?.remove();
            updateBadge(data.cart_count);
            ['cartTotal','cartSubtotal','cartDiscount'].forEach((id) => {
              const key = id === 'cartTotal' ? 'total' : (id === 'cartSubtotal' ? 'subtotal' : 'discount');
              const element = document.getElementById(id); if (element && data[key] !== undefined) element.textContent = formatRub(data[key]);
            });
            showToast(data.message);
          } catch (error) { showToast(error.message || 'Ошибка сети', 'error'); }
        }
      }, true);

      document.addEventListener('click', async (event) => {
        const button = event.target.closest('.cart-qty-btn');
        if (!button) return;
        const id = button.dataset.id;
        const row = document.querySelector(`[data-cart-item-id="${id}"]`);
        const quantityElement = row?.querySelector(`[data-qty-id="${id}"]`);
        const current = Number(quantityElement?.textContent || 1);
        const next = Math.max(1, current + Number(button.dataset.delta || 0));
        if (next === current) return;
        if (quantityElement) quantityElement.textContent = next;
        try {
          const response = await fetch(`${appBase}/cart/update/${id}`, { method:'POST', credentials:'same-origin', headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json','Content-Type':'application/json'}, body:JSON.stringify({quantity:next}) });
          const data = await response.json().catch(() => ({}));
          if (!response.ok || !data.success) throw new Error(data.message || 'Не удалось обновить количество');
          updateBadge(data.cart_count);
          const line = row?.querySelector(`[data-item-total-id="${id}"]`); if (line) line.textContent = formatRub(data.item_total);
          const map = {cartTotal:'total',cartSubtotal:'subtotal',cartDiscount:'discount'};
          Object.entries(map).forEach(([elementId,key]) => { const element=document.getElementById(elementId); if(element && data[key]!==undefined) element.textContent=formatRub(data[key]); });
        } catch (error) { if (quantityElement) quantityElement.textContent = current; showToast(error.message || 'Ошибка сети', 'error'); }
      });

      const endpoint = @json(route('products.suggestions'));
      const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]));
      document.querySelectorAll('[data-search-form]').forEach((form) => {
        const input = form.querySelector('[data-search-input]'); const box = form.querySelector('[data-search-suggestions]');
        let timer; let controller;
        if (!input || !box) return;
        input.addEventListener('input', () => {
          clearTimeout(timer); controller?.abort();
          const query = input.value.trim();
          if (query.length < 2) { box.classList.add('hidden'); box.innerHTML=''; return; }
          timer = setTimeout(async () => {
            controller = new AbortController();
            try {
              const response = await fetch(endpoint+'?q='+encodeURIComponent(query), {headers:{Accept:'application/json'},signal:controller.signal});
              const {items=[]} = await response.json();
              if (input.value.trim() !== query) return;
              box.innerHTML = items.length ? items.map((item) => `<a href="${escapeHtml(item.url)}" class="flex items-center gap-3 rounded-xl p-2 hover:bg-slate-50"><img class="h-10 w-10 rounded-lg bg-slate-50 object-contain" src="${escapeHtml(item.image)}" alt=""><span class="min-w-0 flex-1"><span class="block truncate text-sm font-medium text-slate-900">${escapeHtml(item.name)}</span><span class="block text-xs text-slate-500">${escapeHtml(item.brand)} · ${item.available?'В наличии':'Нет в наличии'}</span></span><span class="text-xs font-semibold">${escapeHtml(item.price)}</span></a>`).join('') : '<p class="px-3 py-2 text-sm text-slate-500">Ничего не найдено</p>';
              box.classList.remove('hidden');
            } catch (error) { if (error.name !== 'AbortError') box.classList.add('hidden'); }
          }, 220);
        });
        input.addEventListener('blur', () => setTimeout(() => box.classList.add('hidden'), 150));
      });

      const analyticsId = @json(config('store.analytics_id'));
      const banner = document.getElementById('cookieBanner');
      function loadAnalytics() {
        if (!analyticsId || window.ym) return;
        window.ym = window.ym || function(){(window.ym.a=window.ym.a||[]).push(arguments)}; window.ym.l=Date.now();
        const script=document.createElement('script'); script.async=true; script.src='https://mc.yandex.ru/metrika/tag.js?id='+encodeURIComponent(analyticsId); document.head.appendChild(script);
        window.ym(Number(analyticsId), 'init', {clickmap:true,trackLinks:true,accurateTrackBounce:true,webvisor:false});
      }
      if (analyticsId) {
        const consent=localStorage.getItem('analytics_consent');
        if (consent==='yes') loadAnalytics(); else if (!consent) banner?.classList.remove('hidden');
        document.querySelector('[data-cookie-accept]')?.addEventListener('click',()=>{localStorage.setItem('analytics_consent','yes');banner?.remove();loadAnalytics()});
        document.querySelector('[data-cookie-reject]')?.addEventListener('click',()=>{localStorage.setItem('analytics_consent','no');banner?.remove()});
      }
    })();
  </script>
  @stack('scripts')
</body>
</html>
