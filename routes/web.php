<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;

// Главная страница
Route::get('/', [HomeController::class, 'welcome'])->name('home');

// Аутентификация
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Страницы
Route::get('/delivery', [PageController::class, 'delivery'])->name('pages.delivery');
Route::get('/returns', [PageController::class, 'returns'])->name('pages.returns');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');
Route::get('/contacts', [PageController::class, 'contacts'])->name('pages.contacts');
Route::post('/contact-submit', [PageController::class, 'contactSubmit'])->name('pages.contact-submit');

// Продукты
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{identifier}', [ProductController::class, 'show'])->name('products.show');
//Route::get('/', [ProductController::class, 'index'])->name('home');


// Заглушки для временно отсутствующих маршрутов
Route::get('/profile', function () {
    return redirect('/')->with('info', 'Страница профиля в разработке');
})->name('profile.edit');

Route::get('/orders', function () {
    return redirect('/')->with('info', 'Страница заказов в разработке');
})->name('orders.index');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
//Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');


// Маршрут для поиска товаров
Route::get('/products/search', [App\Http\Controllers\ProductController::class, 'search'])
    ->name('products.search');