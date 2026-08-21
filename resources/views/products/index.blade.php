@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
  <div class="flex items-center justify-between gap-4">
    <h1 class="text-2xl font-semibold tracking-tight">Каталог</h1>

    {{-- Mobile filters button --}}
    <button id="openFiltersBtn"
      class="lg:hidden inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
      <span>Фильтры</span>
      <span class="text-slate-500">({{ $products->total() }})</span>
    </button>
  </div>

  <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-4">

    {{-- Desktop sidebar --}}
    <aside class="hidden lg:block lg:col-span-1">
      <div id="filtersSidebar" class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
        @include('products.partials.filters')
      </div>
    </aside>

    {{-- Grid --}}
    <section class="lg:col-span-3">
      <div class="relative">
        <div id="gridLoading"
             class="pointer-events-none absolute inset-0 hidden items-center justify-center rounded-3xl bg-white/60 backdrop-blur-sm">
          <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm">
            <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25"></circle>
              <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3"></path>
            </svg>
            Загрузка…
          </div>
        </div>

        <div id="productsGridWrap">
          @include('products.partials.grid')
        </div>
      </div>
    </section>
  </div>
</div>

{{-- Mobile off-canvas --}}
<div id="filtersDrawer" class="fixed inset-0 z-50 hidden">
  <div id="filtersBackdrop" class="absolute inset-0 bg-black/40"></div>

  <div class="absolute right-0 top-0 h-full w-[92%] max-w-md bg-white shadow-2xl">
    <div class="flex items-center justify-between border-b px-5 py-4">
      <div class="text-lg font-semibold">Фильтры</div>
      <button id="closeFiltersBtn" class="rounded-xl px-3 py-2 text-sm font-semibold hover:bg-slate-100">Закрыть</button>
    </div>

    <div id="filtersDrawerInner" class="p-5 overflow-auto h-[calc(100%-64px)]">
      @include('products.partials.filters')
    </div>
  </div>
</div>

<script>
(function () {
  const drawer = document.getElementById('filtersDrawer');
  const openBtn = document.getElementById('openFiltersBtn');
  const closeBtn = document.getElementById('closeFiltersBtn');
  const backdrop = document.getElementById('filtersBackdrop');
  const loading = document.getElementById('gridLoading');

  function openDrawer(){ if(drawer) drawer.classList.remove('hidden'); }
  function closeDrawer(){ if(drawer) drawer.classList.add('hidden'); }

  if(openBtn) openBtn.addEventListener('click', openDrawer);
  if(closeBtn) closeBtn.addEventListener('click', closeDrawer);
  if(backdrop) backdrop.addEventListener('click', closeDrawer);

  let abort = null;
  const timers = new WeakMap();

  function setLoading(on){
    if(!loading) return;
    loading.classList.toggle('hidden', !on);
    loading.classList.toggle('flex', on);
  }

  function buildUrlFromForm(form){
    const url = new URL(form.action, window.location.origin);
    const fd = new FormData(form);

    // убрать пустые значения
    for (const [k, v] of fd.entries()) {
      if(v === '' || v === null) fd.delete(k);
    }

    const params = new URLSearchParams();
    for (const [k, v] of fd.entries()) params.append(k, v);

    url.search = params.toString();
    return url;
  }

  async function fetchAndUpdate(url, { closeMobile = false, push = true } = {}){
    if(abort) abort.abort();
    abort = new AbortController();

    setLoading(true);

    try{
      const res = await fetch(url.toString(), {
        method: 'GET',
        credentials: 'same-origin',
        cache: 'no-store',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json, text/html;q=0.9'
        },
        signal: abort.signal
      });

      const ct = (res.headers.get('content-type') || '').toLowerCase();

      let filtersHtml = null;
      let gridHtml = null;

      if(ct.includes('application/json')){
        const data = await res.json();
        filtersHtml = (typeof data.filtersHtml !== 'undefined') ? data.filtersHtml : null;
        gridHtml    = (typeof data.gridHtml    !== 'undefined') ? data.gridHtml    : null;
      } else {
        // fallback на HTML (на всякий случай)
        const html = await res.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const newGrid = doc.querySelector('#productsGridWrap');
        const newSidebar = doc.querySelector('#filtersSidebar');
        const newDrawer = doc.querySelector('#filtersDrawerInner');

        gridHtml = newGrid ? newGrid.innerHTML : null;
        filtersHtml = (newSidebar || newDrawer)
          ? (newSidebar ? newSidebar.innerHTML : newDrawer.innerHTML)
          : null;
      }

      // apply DOM updates
      const gridWrap = document.getElementById('productsGridWrap');
      if(gridWrap && gridHtml !== null) gridWrap.innerHTML = gridHtml;

      const sidebar = document.getElementById('filtersSidebar');
      const drawerInner = document.getElementById('filtersDrawerInner');
      if(sidebar && filtersHtml !== null) sidebar.innerHTML = filtersHtml;
      if(drawerInner && filtersHtml !== null) drawerInner.innerHTML = filtersHtml;

      // re-bind handlers
      attachHandlers(sidebar);
      attachHandlers(drawerInner);

      if(push){
        window.history.pushState({}, '', url.toString());
      }

      if(closeMobile) closeDrawer();

    }catch(e){
      if(e && e.name === 'AbortError') return;
      console.error('AJAX error:', e);
    }finally{
      setLoading(false);
    }
  }

  function scheduleSubmit(form, opts){
    const prev = timers.get(form);
    if(prev) clearTimeout(prev);

    const t = setTimeout(() => {
      const url = buildUrlFromForm(form);
      fetchAndUpdate(url, opts);
    }, 250);

    timers.set(form, t);
  }

  function attachHandlers(root){
    if(!root) return;
    const form = root.querySelector('form[data-filters-form]');
    if(!form) return;

    // range sync
    const rMin = form.querySelector('[data-range-min]');
    const rMax = form.querySelector('[data-range-max]');
    const iMin = form.querySelector('[data-input-min]');
    const iMax = form.querySelector('[data-input-max]');
    const outMin = form.querySelector('[data-out-min]');
    const outMax = form.querySelector('[data-out-max]');

    function clampRanges(){
      if(!rMin || !rMax || !iMin || !iMax) return;
      let min = parseInt(rMin.value || 0, 10);
      let max = parseInt(rMax.value || 0, 10);
      if(min > max){ const tmp=min; min=max; max=tmp; }
      rMin.value = min; rMax.value = max;
      iMin.value = min; iMax.value = max;
      if(outMin) outMin.textContent = min.toLocaleString('ru-RU') + ' ₽';
      if(outMax) outMax.textContent = max.toLocaleString('ru-RU') + ' ₽';
    }

    if(rMin && rMax && iMin && iMax){
      rMin.addEventListener('input', () => { clampRanges(); scheduleSubmit(form, { closeMobile:false }); });
      rMax.addEventListener('input', () => { clampRanges(); scheduleSubmit(form, { closeMobile:false }); });
      iMin.addEventListener('change', () => { rMin.value = iMin.value || rMin.min; clampRanges(); scheduleSubmit(form, { closeMobile:false }); });
      iMax.addEventListener('change', () => { rMax.value = iMax.value || rMax.max; clampRanges(); scheduleSubmit(form, { closeMobile:false }); });
      clampRanges();
    }

    // 🔥 ВАЖНО: не слушаем change на всей форме — только на помеченных инпутах
    form.querySelectorAll('[data-filter-input]').forEach((input) => {
      input.addEventListener('change', (e) => {
        e.preventDefault();
        e.stopPropagation();
        scheduleSubmit(form, { closeMobile:false });
      });
    });

    // submit (кнопка "Применить" на мобилке)
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const url = buildUrlFromForm(form);
      fetchAndUpdate(url, { closeMobile:true });
    });

    // reset link closes drawer
    const resetLink = form.querySelector('[data-reset-link]');
    if(resetLink){
      resetLink.addEventListener('click', () => closeDrawer());
    }
  }

  // ✅ Pagination intercept (твой рабочий селектор)
  document.addEventListener('click', (e) => {
    const a = e.target.closest('#productsGridWrap .pagination a[href]');
    if(!a) return;

    e.preventDefault();
    const url = new URL(a.getAttribute('href'), window.location.origin);
    fetchAndUpdate(url, { closeMobile:false });
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // back/forward
  window.addEventListener('popstate', () => {
    const url = new URL(window.location.href);
    fetchAndUpdate(url, { closeMobile:false, push:false });
  });

  // init
  attachHandlers(document.getElementById('filtersSidebar'));
  attachHandlers(document.getElementById('filtersDrawerInner'));
})();
</script>
@endsection
