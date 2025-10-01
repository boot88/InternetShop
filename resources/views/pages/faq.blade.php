@extends('layouts.app')

@section('title', 'Частые вопросы')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            @foreach($breadcrumbs as $breadcrumb)
            <li>
                @if(!$loop->last)
                <a href="{{ $breadcrumb['url'] }}" class="text-gray-500 hover:text-gray-700">{{ $breadcrumb['name'] }}</a>
                @else
                <span class="text-gray-400">{{ $breadcrumb['name'] }}</span>
                @endif
            </li>
            @if(!$loop->last)
            <li>
                <svg class="flex-shrink-0 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </li>
            @endif
            @endforeach
        </ol>
    </nav>

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Частые вопросы</h1>

    <div class="space-y-4">
        @foreach($faqs as $faq)
        <div class="bg-white rounded-lg shadow-md" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
            <button 
                @click="open = !open" 
                class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 rounded-lg"
            >
                <span class="font-semibold text-gray-900">{{ $faq['question'] }}</span>
                <svg 
                    class="w-5 h-5 text-gray-500 transition-transform duration-200" 
                    :class="{ 'transform rotate-180': open }" 
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-collapse class="px-6 pb-4 text-gray-600">
                {{ $faq['answer'] }}
            </div>
        </div>
        @endforeach
    </div>

    <!-- Дополнительная помощь -->
    <div class="mt-12 bg-blue-50 rounded-lg p-6 text-center">
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Не нашли ответ на свой вопрос?</h3>
        <p class="text-gray-600 mb-4">Свяжитесь с нашей службой поддержки, мы всегда готовы помочь!</p>
        <a href="{{ route('pages.contacts') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
            Связаться с нами
        </a>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('faq', () => ({
            open: false
        }))
    })
</script>
@endsection