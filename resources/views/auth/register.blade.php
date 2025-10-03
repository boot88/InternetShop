<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#1E40AF',
                        accent: '#6366F1',
                        dark: '#1F2937',
                        light: '#F9FAFB'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Навигация -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <i class="fas fa-laptop-code text-2xl text-primary mr-2"></i>
                        <span class="text-xl font-bold text-dark">TechStore</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-primary transition duration-150">
                        <i class="fas fa-home mr-1"></i>Главная
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary transition duration-150">
                        Вход
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-12">
        <div class="max-w-md w-full mx-auto">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-primary to-accent py-6 px-8">
                    <div class="flex items-center">
                        <i class="fas fa-user-plus text-white text-2xl mr-3"></i>
                        <h2 class="text-2xl font-bold text-white">Создать аккаунт</h2>
                    </div>
                    <p class="text-blue-100 mt-2">Присоединяйтесь к TechStore сегодня</p>
                </div>
                
                <form class="p-8 space-y-6" action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="name" required 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-150"
                                   placeholder="Ваше имя" value="{{ old('name') }}">
                        </div>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="email" required 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-150"
                                   placeholder="Email адрес" value="{{ old('email') }}">
                        </div>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="password" required 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-150"
                                   placeholder="Пароль">
                        </div>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="password_confirmation" required 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-150"
                                   placeholder="Подтвердите пароль">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" required>
                        <label for="terms" class="ml-2 block text-sm text-gray-700">
                            Я согласен с <a href="#" class="text-primary hover:text-secondary">условиями использования</a> и <a href="#" class="text-primary hover:text-secondary">политикой конфиденциальности</a>
                        </label>
                    </div>

                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <div class="flex">
                                <i class="fas fa-exclamation-circle text-red-500 mr-2 mt-0.5"></i>
                                <div>
                                    @foreach($errors->all() as $error)
                                        <p class="text-red-700 text-sm">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-primary to-accent hover:from-secondary hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-300 shadow-md">
                            <i class="fas fa-user-plus mr-2"></i>
                            Создать аккаунт
                        </button>
                    </div>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Или зарегистрируйтесь с помощью</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" class="w-full inline-flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                            <i class="fab fa-google text-red-500 mr-2"></i>
                            Google
                        </button>
                        <button type="button" class="w-full inline-flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                            <i class="fab fa-facebook text-blue-600 mr-2"></i>
                            Facebook
                        </button>
                    </div>

                    <div class="text-center pt-4">
                        <p class="text-gray-600">
                            Уже есть аккаунт?
                            <a href="{{ route('login') }}" class="font-medium text-primary hover:text-secondary transition duration-150 ml-1">
                                Войдите
                            </a>
                        </p>
                    </div>
                </form>
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ url('/') }}" class="inline-flex items-center text-gray-600 hover:text-primary transition duration-150">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Вернуться на главную страницу
                </a>
            </div>
        </div>
    </div>
</body>
</html>