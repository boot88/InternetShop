@extends('layouts.app')

@section('title', 'Каталог — '.config('store.name', 'TechZone'))
@section('meta_description', 'Каталог электроники с фильтрами по категории, бренду, цене и наличию.')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div><p class="text-sm font-semibold text-indigo-600">TechZone</p><h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">Каталог</h1></div>

    <button id="openFiltersBtn" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-50 lg:hidden"><span>Фильтры</span><span data-mobile-products-total class="rounded-full bg-slate-100 px-1.5 py-0.5 text-xs text-slate-500">{{ $products->total() }}</span></button>
    <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2 text-sm">
      @foreach(request()->except('sort', 'page') as $key => $value)
        @if(is_array($value))
          @foreach($value as $nestedValue)<input type="hidden" name="{{ $key }}[]" value="{{ $nestedValue }}">@endforeach
        @else
          <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
      @endforeach
      <label for="catalog-sort" class="hidden sm:block text-slate-600">Сортировка</label>
      <select id="catalog-sort" name="sort" onchange="this.form.submit()" class="rounded-xl border-slate-200 bg-white py-2 pl-3 pr-8 font-medium focus:border-indigo-500 focus:ring-indigo-500">
        <option value="recommended" @selected($sort === 'recommended')>Рекомендуемые</option>
        <option value="newest" @selected($sort === 'newest')>Недавно добавленные</option>
        <option value="price_asc" @selected($sort === 'price_asc')>Сначала дешевле</option>
        <option value="price_desc" @selected($sort === 'price_desc')>Сначала дороже</option>
      </select>
    </form>
  </div>

  <div id="quickCategoriesWrap">@include('products.partials.quick-categories')</div>

  @include('products.partials.catalog-meta')
  <div id="catalogError" class="mt-4 hidden rounded-xl bg-rose-50 p-3 text-sm text-rose-800" role="alert">Не удалось обновить каталог. Проверьте соединение и повторите попытку.</div>

  <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">

    {{-- Desktop sidebar --}}
    <aside class="hidden lg:block">
      <div id="filtersSidebar" class="sticky top-20 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        @include('products.partials.filters')
      </div>
    </aside>

    {{-- Grid --}}
    <section>
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

<div id="quickView" class="fixed inset-0 z-[60] hidden" aria-hidden="true">
  <div data-quick-view-close class="absolute inset-0 bg-slate-950/40"></div>
  <section class="absolute inset-x-4 top-1/2 mx-auto max-w-lg -translate-y-1/2 rounded-3xl bg-white p-5 shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="quickViewName">
    <button type="button" data-quick-view-close class="absolute right-4 top-4 rounded-lg p-2 text-slate-500 hover:bg-slate-100" aria-label="Закрыть">×</button>
    <div class="grid grid-cols-[128px_1fr] gap-4">
      <div class="aspect-square rounded-2xl bg-slate-50 p-2"><img id="quickViewImage" class="h-full w-full object-contain" alt=""></div>
      <div>
        <p id="quickViewBrand" class="text-xs text-slate-500"></p>
        <h2 id="quickViewName" class="mt-1 pr-8 text-lg font-semibold text-slate-900"></h2>
        <p id="quickViewPrice" class="mt-3 text-xl font-semibold"></p>
        <p id="quickViewStock" class="mt-2 text-sm"></p>
        <a id="quickViewLink" class="mt-4 inline-flex rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Открыть товар</a>
      </div>
    </div>
  </section>
</div>

{{-- Mobile off-canvas --}}
<div id="filtersDrawer" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="filtersDrawerTitle">
  <div id="filtersBackdrop" class="absolute inset-0 bg-black/40"></div>

  <div class="absolute right-0 top-0 h-full w-[92%] max-w-md bg-white shadow-2xl">
    <div class="flex items-center justify-between border-b px-5 py-4">
      <div id="filtersDrawerTitle" class="text-lg font-semibold">Фильтры</div>
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

  function openDrawer(){ if(drawer){ drawer.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); closeBtn?.focus(); } }
  function closeDrawer(){ if(drawer){ drawer.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); openBtn?.focus(); } }

  if(openBtn) openBtn.addEventListener('click', openDrawer);
  if(closeBtn) closeBtn.addEventListener('click', closeDrawer);
  if(backdrop) backdrop.addEventListener('click', closeDrawer);
  document.addEventListener('keydown', (event) => { if(event.key === 'Escape' && !drawer?.classList.contains('hidden')) closeDrawer(); });
  drawer?.addEventListener('keydown', (event) => {
    if(event.key !== 'Tab') return;
    const focusable = [...drawer.querySelectorAll('button, a, input, select, summary, [tabindex]:not([tabindex="-1"])')].filter((element) => !element.disabled && element.offsetParent !== null);
    if(!focusable.length) return;
    const first = focusable[0], last = focusable[focusable.length - 1];
    if(event.shiftKey && document.activeElement === first){ event.preventDefault(); last.focus(); }
    else if(!event.shiftKey && document.activeElement === last){ event.preventDefault(); first.focus(); }
  });

  let abort = null;

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
      let catalogMetaHtml = null;
      let quickCategoriesHtml = null;

      if(ct.includes('application/json')){
        const data = await res.json();
        filtersHtml = (typeof data.filtersHtml !== 'undefined') ? data.filtersHtml : null;
        gridHtml    = (typeof data.gridHtml    !== 'undefined') ? data.gridHtml    : null;
        catalogMetaHtml = (typeof data.catalogMetaHtml !== 'undefined') ? data.catalogMetaHtml : null;
        quickCategoriesHtml = (typeof data.quickCategoriesHtml !== 'undefined') ? data.quickCategoriesHtml : null;
        document.querySelectorAll('[data-mobile-products-total]').forEach((element) => element.textContent = String(data.total ?? ''));
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

      const catalogMeta = document.getElementById('catalogMeta');
      if(catalogMeta && catalogMetaHtml !== null) catalogMeta.outerHTML = catalogMetaHtml;
      const quickCategories = document.getElementById('quickCategoriesWrap');
      if(quickCategories && quickCategoriesHtml !== null) quickCategories.innerHTML = quickCategoriesHtml;

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

      document.getElementById('catalogError')?.classList.add('hidden');

      if(closeMobile) closeDrawer();

    }catch(e){
      if(e && e.name === 'AbortError') return;
      console.error('AJAX error:', e);
      document.getElementById('catalogError')?.classList.remove('hidden');
    }finally{
      setLoading(false);
    }
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
      rMin.addEventListener('input', clampRanges);
      rMax.addEventListener('input', clampRanges);
      iMin.addEventListener('change', () => { rMin.value = iMin.value || rMin.min; clampRanges(); });
      iMax.addEventListener('change', () => { rMax.value = iMax.value || rMax.max; clampRanges(); });
      clampRanges();
    }

    const brandSearch = form.querySelector('[data-brand-search]');
    const brandRows = [...form.querySelectorAll('[data-brand-row]')];
    const toggleBrands = form.querySelector('[data-toggle-brands]');
    let allBrandsVisible = false;
    brandSearch?.addEventListener('input', () => {
      const query = brandSearch.value.trim().toLowerCase();
      brandRows.forEach((row, index) => {
        const matches = row.textContent.toLowerCase().includes(query);
        row.classList.toggle('hidden', !matches || (!query && !allBrandsVisible && index >= 6 && !row.querySelector('input').checked));
      });
      if(toggleBrands) toggleBrands.classList.toggle('hidden', query.length > 0);
    });
    toggleBrands?.addEventListener('click', () => {
      allBrandsVisible = !allBrandsVisible;
      brandRows.forEach((row, index) => { if(index >= 6) row.classList.toggle('hidden', !allBrandsVisible && !row.querySelector('input').checked); });
      toggleBrands.textContent = allBrandsVisible ? 'Свернуть бренды' : 'Показать все бренды';
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

  const quickView = document.getElementById('quickView');
  document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-quick-view]');
    if (trigger) {
      document.getElementById('quickViewName').textContent = trigger.dataset.name || '';
      document.getElementById('quickViewBrand').textContent = trigger.dataset.brand || '';
      document.getElementById('quickViewPrice').textContent = trigger.dataset.price || '';
      document.getElementById('quickViewStock').textContent = trigger.dataset.stock || '';
      document.getElementById('quickViewImage').src = trigger.dataset.image || '';
      document.getElementById('quickViewImage').alt = trigger.dataset.name || '';
      document.getElementById('quickViewLink').href = trigger.dataset.url || '#';
      quickView?.classList.remove('hidden');
      quickView?.setAttribute('aria-hidden', 'false');
      document.body.classList.add('overflow-hidden');
      quickView?.querySelector('button[data-quick-view-close]')?.focus();
    }
    if (event.target.closest('[data-quick-view-close]')) {
      quickView?.classList.add('hidden');
      quickView?.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('overflow-hidden');
    }
  });
  document.addEventListener('keydown', (event) => {
    if(event.key === 'Escape' && quickView && !quickView.classList.contains('hidden')){
      quickView.classList.add('hidden'); quickView.setAttribute('aria-hidden','true'); document.body.classList.remove('overflow-hidden');
    }
  });
})();
</script>
@endsection
