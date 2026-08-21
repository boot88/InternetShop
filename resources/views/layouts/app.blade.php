<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="app-base" content="{{ rtrim(url('/'), '/') }}">
  <title>@yield('title', config('app.name', 'TechZone'))</title>
  
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
  <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" sizes="32x32">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}" sizes="180x180">  
  
  <!-- Tailwind (CDN, чтобы ничего не "летело" без сборки Vite) -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Alpine.js (мобильное меню/дропдауны) -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    [x-cloak]{ display:none !important; }
  </style>

  @stack('head')
  
  
  <!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=106844146', 'ym');

    ym(106844146, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/106844146" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
  
  
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 flex flex-col overflow-x-hidden">
  <header class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/80 backdrop-blur" x-data="{ open:false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between gap-3">
        <!-- Brand -->
        <a href="{{ route('home') }}" class="flex items-center gap-2">
          <span class="grid h-9 w-9 place-items-center rounded-xl bg-indigo-600 text-white font-black">T</span>
          <span class="font-semibold tracking-tight text-slate-900">TechZone</span>
        </a>

        <!-- Search (desktop) -->
        <form action="{{ route('products.search') }}" method="GET" class="hidden md:block w-full max-w-xl">
          <div class="relative">
            <input name="q" value="{{ request('q') }}" placeholder="Поиск: смартфоны, ноутбуки, наушники…"
              class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 pr-12 text-sm outline-none
                     focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400" />
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-xl px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
              Найти
            </button>
          </div>
        </form>

        <!-- Actions -->
        <div class="flex items-center gap-2">
          <a href="{{ route('deals') }}" class="hidden sm:inline-flex rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Акции</a>
          <a href="{{ route('products.index') }}" class="hidden sm:inline-flex rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Каталог</a>

          <a href="{{ route('cart.index') }}" class="relative inline-flex items-center rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Корзина
            <span id="cartCountBadge" class="ml-2 inline-flex items-center justify-center rounded-full bg-indigo-600 px-2 py-0.5 text-xs font-semibold text-white">
              {{ $cartCount ?? 0 }}
            </span>
          </a>

          <!-- Mobile toggle -->
          <button type="button"
                  class="md:hidden inline-flex items-center justify-center rounded-xl p-2 hover:bg-slate-100"
                  @click="open = !open"
                  aria-label="Открыть меню">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile dropdown -->
      <div class="md:hidden pb-3" x-cloak x-show="open" @click.outside="open=false">
        <form action="{{ route('products.search') }}" method="GET" class="mt-3">
          <input name="q" value="{{ request('q') }}" placeholder="Поиск товаров…"
                 class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400"/>
        </form>
        <div class="mt-3 grid grid-cols-2 gap-2">
          <a href="{{ route('products.index') }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium hover:bg-slate-50">Каталог</a>
          <a href="{{ route('deals') }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium hover:bg-slate-50">Акции</a>
          <a href="{{ route('delivery') }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium hover:bg-slate-50">Доставка</a>
          <a href="{{ route('returns') }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium hover:bg-slate-50">Возврат</a>
          <a href="{{ route('faq') }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium hover:bg-slate-50">FAQ</a>
          <a href="{{ route('contacts') }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium hover:bg-slate-50">Контакты</a>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-1">
    @yield('content')
  </main>

  <footer class="border-t border-slate-200/70 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
      <div class="grid gap-8 md:grid-cols-4">
        <div class="space-y-2">
          <div class="flex items-center gap-2">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-indigo-600 text-white font-black">T</span>
            <span class="font-semibold text-slate-900">TechZone</span>
          </div>
          <p class="text-sm text-slate-600">Интернет-магазин электроники: смартфоны, ноутбуки, аксессуары.</p>
        </div>
        <div class="space-y-2 text-sm">
          <div class="font-semibold text-slate-900">Покупателям</div>
          <a class="block text-slate-600 hover:text-slate-900" href="{{ route('delivery') }}">Доставка</a>
          <a class="block text-slate-600 hover:text-slate-900" href="{{ route('returns') }}">Возврат</a>
          <a class="block text-slate-600 hover:text-slate-900" href="{{ route('faq') }}">FAQ</a>
        </div>
        <div class="space-y-2 text-sm">
          <div class="font-semibold text-slate-900">Магазин</div>
          <a class="block text-slate-600 hover:text-slate-900" href="{{ route('products.index') }}">Каталог</a>
          <a class="block text-slate-600 hover:text-slate-900" href="{{ route('deals') }}">Акции</a>
          <a class="block text-slate-600 hover:text-slate-900" href="{{ route('contacts') }}">Контакты</a>
        </div>
        <div class="space-y-2 text-sm">
          <div class="font-semibold text-slate-900">Поддержка</div>
          <p class="text-slate-600">Пн–Вс 10:00–20:00</p>
          <p class="text-slate-600">info@Marketing.com</p>
        </div>
      </div>
      <div class="mt-10 text-xs text-slate-500">© {{ date('Y') }} TechZone</div>
    </div>
  </footer>


  <!-- Toast -->
  <div id="toastWrap" class="fixed top-4 right-4 z-[9999] pointer-events-none hidden">
    <div id="toast" class="pointer-events-auto flex max-w-sm items-start gap-3 rounded-2xl bg-slate-900 text-white px-4 py-3 shadow-lg ring-1 ring-white/10 opacity-0 translate-y-[-8px] transition duration-200">
      <div class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white text-sm font-bold" aria-hidden="true">✓</div>
      <div class="min-w-0 flex-1">
        <div id="toastMsg" class="text-sm font-medium leading-5">Добавлено</div>
        <div class="mt-2 flex flex-wrap gap-2">
          <a id="toastGoCart" href="{{ route('cart.index') }}" class="inline-flex items-center rounded-xl bg-white/10 px-3 py-1.5 text-xs font-semibold hover:bg-white/20">Перейти в корзину</a>
          <button id="toastClose" type="button" class="inline-flex items-center rounded-xl bg-white/0 px-3 py-1.5 text-xs font-semibold hover:bg-white/10">Закрыть</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function () {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const badge = document.getElementById('cartCountBadge');

      const wrap = document.getElementById('toastWrap');
      const toast = document.getElementById('toast');
      const msgEl = document.getElementById('toastMsg');
      const closeBtn = document.getElementById('toastClose');

      let toastTimer = null;

      function showToast(message) {
        if (!wrap || !toast || !msgEl) return;
        msgEl.textContent = message || 'Готово';
        wrap.classList.remove('hidden');
        // animate in
        requestAnimationFrame(() => {
          toast.classList.remove('opacity-0', 'translate-y-[-8px]');
          toast.classList.add('opacity-100', 'translate-y-0');
        });

        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(hideToast, 2400);
      }

      function hideToast() {
        if (!wrap || !toast) return;
        toast.classList.add('opacity-0', 'translate-y-[-8px]');
        toast.classList.remove('opacity-100', 'translate-y-0');
        setTimeout(() => wrap.classList.add('hidden'), 180);
      }

      closeBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        hideToast();
      });

      async function postJSON(url, data) {
        const res = await fetch(url, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data || {})
        });
        return res;
      }

      function toNumber(val){
        if (typeof val === 'number') return val;
        if (typeof val === 'string') {
          const s = val.replace(/\s+/g,'').replace(/,/g,'.').replace(/[^\d.]/g,'');
          return parseFloat(s || '0') || 0;
        }
        return 0;
      }
      function formatRub(n) {
        const num = toNumber(n);
        try { return new Intl.NumberFormat('ru-RU').format(num) + ' ₽'; }
        catch(e){ return (num || 0) + ' ₽'; }
      }

      function updateBadge(count) {
        if (badge && count !== undefined && count !== null) badge.textContent = String(count);
      }

      // ✅ Add-to-cart AJAX (works for any form posting to /cart/add/*)
      document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;

        const action = form.getAttribute('action') || '';
        const isAddToCart = form.hasAttribute('data-add-to-cart') || /\/cart\/add\/\d+/.test(action);

        if (!isAddToCart) return;

        e.preventDefault();

        const btn = form.querySelector('[data-add-to-cart-button], button[type="submit"], input[type="submit"]');
        const prevText = btn?.textContent;
        if (btn) { btn.disabled = true; btn.classList.add('opacity-70'); }

        try {
          const fd = new FormData(form);
          const res = await fetch(action, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'X-CSRF-TOKEN': csrf,
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            },
            body: fd
          });

          const data = await res.json().catch(() => null);

          if (res.ok && data && data.success) {
            updateBadge(data.cart_count);
            showToast(data.message || 'Добавлено в корзину');
          } else {
            showToast((data && (data.message || data.error)) || 'Не удалось добавить в корзину');
          }
        } catch (err) {
          showToast('Ошибка сети при добавлении');
        } finally {
          if (btn) { btn.disabled = false; btn.classList.remove('opacity-70'); if (prevText) btn.textContent = prevText; }
        }
      }, true);

      // ✅ Cart quantity +/- buttons (no reload)
      document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.cart-qty-btn');
        if (!btn) return;

        const id = btn.getAttribute('data-id');
        const delta = parseInt(btn.getAttribute('data-delta') || '0', 10);
        if (!id || !delta) return;

        e.preventDefault();

        const row = document.querySelector(`[data-cart-item-id="${id}"]`);
        const qtyEl = row?.querySelector(`[data-qty-id="${id}"]`);
        const price = parseFloat(row?.getAttribute('data-price') || '0');

        const currentQty = parseInt(qtyEl?.textContent || '1', 10) || 1;
        const nextQty = Math.max(1, currentQty + delta);

        // optimistic UI
        if (qtyEl) qtyEl.textContent = String(nextQty);

        try {
          const APP_BASE = document.querySelector('meta[name="app-base"]')?.content || '';
      const url = `${APP_BASE}/cart/update/${id}`;
          const res = await postJSON(url, { quantity: nextQty });

          const data = await res.json().catch(() => null);

          if (res.ok && data && data.success) {
            updateBadge(data.cart_count);
            // update line total
            const lineEl = row?.querySelector(`[data-item-total-id="${id}"]`);
            if (lineEl) {
              const lineTotal = (data.item_total !== undefined) ? data.item_total : (price * nextQty);
              lineEl.textContent = formatRub(lineTotal);
            }
            // update cart total if provided
            const cartTotalEl = document.getElementById('cartTotal');
            if (cartTotalEl) {
              if (data.total !== undefined) cartTotalEl.textContent = formatRub(data.total);
              else {
                // fallback: sum line totals
                const sum = Array.from(document.querySelectorAll('[data-item-total-id]'))
                  .map(el => parseFloat((el.textContent || '').replace(/[^\d.]/g,'') || '0'))
                  .reduce((a,b)=>a+b,0);
                cartTotalEl.textContent = formatRub(sum);
              }
            }
          } else {
            // rollback on error
            if (qtyEl) qtyEl.textContent = String(currentQty);
            showToast((data && (data.message || data.error)) || 'Не удалось обновить количество');
          }
        } catch (err) {
          if (qtyEl) qtyEl.textContent = String(currentQty);
          showToast('Ошибка сети при обновлении');
        }
      });

      // ✅ Remove item (AJAX)
      document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        const action = form.getAttribute('action') || '';
        const isRemove = form.hasAttribute('data-cart-remove') || /\/cart\/remove\/\d+/.test(action);
        const isClear = form.hasAttribute('data-cart-clear') || /\/cart\/clear/.test(action);

        if (!isRemove && !isClear) return;

        e.preventDefault();


        try {
          const fd = new FormData(form);
          const res = await fetch(action, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'X-CSRF-TOKEN': csrf,
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            },
            body: fd
          });
          const data = await res.json().catch(() => null);

          if (res.ok && data && data.success) {
            updateBadge(data.cart_count);

            if (isRemove) {
              const idMatch = action.match(/\/cart\/remove\/(\d+)/);
              const id = idMatch ? idMatch[1] : null;
              const row = id ? document.querySelector(`[data-cart-item-id="${id}"]`) : null;
              row?.remove();
            }

            if (isClear) {
              // simplest: reload to show empty state
              window.location.reload();
              return;
            }

            // update total
            const cartTotalEl = document.getElementById('cartTotal');
            if (cartTotalEl && data.total !== undefined) cartTotalEl.textContent = formatRub(data.total);

            showToast(data.message || (isRemove ? 'Удалено' : 'Готово'));
          } else {
            showToast((data && (data.message || data.error)) || 'Операция не выполнена');
          }
        } catch (err) {
          showToast('Ошибка сети');
        }
      }, true);
    })();
  </script>

  @stack('scripts')
</body>
</html>
