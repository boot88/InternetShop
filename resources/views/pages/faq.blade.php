@extends('layouts.app')
@section('title', 'Частые вопросы — '.config('store.name', 'TechZone'))
@section('meta_description', 'Ответы об оформлении, доставке, оплате и гарантии.')
@push('head')
<script type="application/ld+json">@json(['@context'=>'https://schema.org','@type'=>'FAQPage','mainEntity'=>collect($faqs)->map(fn($faq)=>['@type'=>'Question','name'=>$faq['question'],'acceptedAnswer'=>['@type'=>'Answer','text'=>$faq['answer']])->values()], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)</script>
@endpush
@section('content')
<section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
  <p class="text-sm font-semibold text-indigo-600">ПОМОЩЬ</p><h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Частые вопросы</h1>
  <div class="mt-8 space-y-3">
    @foreach($faqs as $faq)
      <details class="group rounded-2xl bg-white ring-1 ring-slate-200" @if($loop->first) open @endif><summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 font-semibold text-slate-900"><span>{{ $faq['question'] }}</span><span class="text-xl text-slate-400 group-open:rotate-45">+</span></summary><p class="border-t border-slate-100 px-5 py-4 text-sm leading-6 text-slate-600">{{ $faq['answer'] }}</p></details>
    @endforeach
  </div>
  <div class="mt-8 rounded-2xl bg-indigo-50 p-6"><h2 class="font-semibold text-slate-950">Нужна помощь?</h2><p class="mt-2 text-sm text-slate-600">Напишите нам и укажите модель товара или номер заказа.</p><a href="{{ route('contacts') }}" class="mt-4 inline-flex rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white">Связаться с магазином</a></div>
</section>
@endsection
