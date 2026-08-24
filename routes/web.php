<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;

// Главная
Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
//Route::get('/', [HomeController::class, 'index'])->name('home');


// Страницы (короткие имена — чтобы route('delivery') работал)
Route::get('/delivery',  [PageController::class, 'delivery'])->name('delivery');
Route::get('/deals',     [PageController::class, 'deals'])->name('deals');
Route::get('/returns',   [PageController::class, 'returns'])->name('returns');
Route::get('/faq',       [PageController::class, 'faq'])->name('faq');
Route::get('/contacts',  [PageController::class, 'contacts'])->name('contacts');
Route::get('/about',     [PageController::class, 'about'])->name('about');
Route::post('/contact-submit', [PageController::class, 'contactSubmit'])->name('contact.submit');

// Аутентификация
Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// Товары (ВАЖНО: search ДО {slug})
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/suggestions', [ProductController::class, 'suggestions'])->name('products.suggestions');
Route::get('/products',        [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Корзина
Route::get('/cart',                 [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}',  [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}',    [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}',  [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',          [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count',           [CartController::class, 'getCartCount'])->name('cart.count');

// Оформление заказа доступно и гостям: контактные данные собираются в форме.
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Заглушки
Route::get('/profile', fn() => redirect('/')->with('info', 'Страница профиля в разработке'))->name('profile.edit');
Route::get('/orders',  fn() => redirect('/')->with('info', 'Страница заказов в разработке'))->name('orders.index');
