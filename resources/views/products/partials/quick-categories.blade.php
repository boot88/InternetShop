<div class="mt-5 flex flex-wrap gap-2">
  <a href="{{ route('products.index', request()->except(['category', 'page'])) }}" class="rounded-full px-4 py-2 text-sm font-medium {{ !$selectedCategory ? 'bg-slate-900 text-white' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50' }}">Все товары</a>
  @foreach($quickCategories as $category)
    <a href="{{ route('products.index', array_merge(request()->except(['category', 'page']), ['category' => $category->id])) }}" class="rounded-full px-4 py-2 text-sm font-medium {{ $selectedCategory == $category->id ? 'bg-slate-900 text-white' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50' }}">{{ $category->name }}</a>
  @endforeach
  @if($selectedCategory && !$quickCategories->contains('id', $selectedCategory))
    @php $selectedQuickCategory = $categories->firstWhere('id', $selectedCategory); @endphp
    @if($selectedQuickCategory)<span class="rounded-full bg-slate-900 px-4 py-2 text-sm font-medium text-white">{{ $selectedQuickCategory->name }}</span>@endif
  @endif
</div>
