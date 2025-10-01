<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>@yield('title', config('app.name', 'Интернет-магазин'))</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js для интерактивных элементов -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .breadcrumb-arrow {
            color: #9CA3AF;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-600 flex items-center">
                        <i class="fas fa-laptop-code mr-2"></i>
                        {{ config('app.name', 'TechShop') }}
                    </a>
                </div>
                
                <!-- Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-200">
                        <i class="fas fa-home mr-1"></i>Главная
                    </a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-200">
                        <i class="fas fa-shopping-bag mr-1"></i>Каталог
                    </a>
                    <a href="{{ route('pages.delivery') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-200">
                        <i class="fas fa-shipping-fast mr-1"></i>Доставка
                    </a>
                    <a href="{{ route('pages.contacts') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-200">
                        <i class="fas fa-phone mr-1"></i>Контакты
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t py-4">
                <div class="flex flex-col space-y-4">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                        <i class="fas fa-home mr-2"></i>Главная
                    </a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                        <i class="fas fa-shopping-bag mr-2"></i>Каталог
                    </a>
                    <a href="{{ route('pages.delivery') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                        <i class="fas fa-shipping-fast mr-2"></i>Доставка
                    </a>
                    <a href="{{ route('pages.contacts') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                        <i class="fas fa-phone mr-2"></i>Контакты
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Breadcrumbs Section -->
    @hasSection('breadcrumbs')
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            @yield('breadcrumbs')
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-laptop-code mr-2 text-blue-400"></i>
                        {{ config('app.name', 'TechShop') }}
                    </h3>
                    <p class="text-gray-400 text-sm">
                        Лучшие технологии по доступным ценам. Мы предлагаем качественную электронику и аксессуары.
                    </p>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="font-semibold mb-4">Категории</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>
                            <a href="{{ route('products.index') }}?category=1" class="hover:text-white transition duration-200 flex items-center">
                                <i class="fas fa-mobile-alt mr-2 text-sm"></i>Смартфоны
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}?category=2" class="hover:text-white transition duration-200 flex items-center">
                                <i class="fas fa-laptop mr-2 text-sm"></i>Ноутбуки
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}?category=3" class="hover:text-white transition duration-200 flex items-center">
                                <i class="fas fa-tv mr-2 text-sm"></i>Телевизоры
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Help -->
                <div>
                    <h4 class="font-semibold mb-4">Помощь</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>
                            <a href="{{ route('pages.delivery') }}" class="hover:text-white transition duration-200 flex items-center">
                                <i class="fas fa-shipping-fast mr-2 text-sm"></i>Доставка и оплата
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.returns') }}" class="hover:text-white transition duration-200 flex items-center">
                                <i class="fas fa-undo mr-2 text-sm"></i>Возврат товара
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.faq') }}" class="hover:text-white transition duration-200 flex items-center">
                                <i class="fas fa-question-circle mr-2 text-sm"></i>Частые вопросы
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contacts -->
                <div>
                    <h4 class="font-semibold mb-4">Контакты</h4>
                    <div class="space-y-2 text-gray-400">
                        <p class="flex items-center">
                            <i class="fas fa-phone mr-2 text-sm"></i>
                            +7 (999) 123-45-67
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-sm"></i>
                            info@techshop.ru
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-sm"></i>
                            г. Москва, ул. Технологическая, д. 15
                        </p>
                        <p class="flex items-center text-sm">
                            <i class="fas fa-clock mr-2"></i>
                            Пн-Пт: 9:00-20:00, Сб-Вс: 10:00-18:00
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'TechShop') }}. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <script>
        // Инициализация Alpine.js для мобильного меню
        document.addEventListener('alpine:init', () => {
            Alpine.data('header', () => ({
                mobileMenuOpen: false
            }))
        });

        // Закрытие мобильного меню при клике на ссылку
        document.addEventListener('DOMContentLoaded', function() {
            const mobileLinks = document.querySelectorAll('.md:hidden a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    Alpine.store('header').mobileMenuOpen = false;
                });
            });
        });
    </script>

    <!-- Дополнительные скрипты из секций -->
    @yield('scripts')
</body>
</html>