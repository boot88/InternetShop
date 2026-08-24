<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class PageController extends Controller
{
    public function delivery(): View
    {
        return view('pages.delivery', ['store' => config('store')]);
    }

    public function returns(): View
    {
        return view('pages.returns', ['store' => config('store')]);
    }

    public function faq(): View
    {
        $faqs = [
            ['question' => 'Как оформить заказ?', 'answer' => 'Добавьте товары в корзину и заполните форму оформления. До оплаты менеджер подтвердит наличие, стоимость и срок доставки.'],
            ['question' => 'Сколько стоит доставка?', 'answer' => config('store.delivery_note')],
            ['question' => 'Какие способы оплаты доступны?', 'answer' => 'При оформлении можно выбрать оплату при получении или запросить счёт для юридического лица и ИП. Доступность выбранного способа подтверждает менеджер.'],
            ['question' => 'Можно ли изменить адрес?', 'answer' => 'Да, пока заказ не передан в доставку. Сообщите номер заказа через страницу контактов.'],
            ['question' => 'Какая гарантия на товар?', 'answer' => config('store.warranty_note')],
            ['question' => 'Где посмотреть заказ?', 'answer' => 'Войдите в аккаунт и откройте раздел «Мои заказы». Гостевой заказ доступен на странице подтверждения в текущем браузере.'],
        ];

        return view('pages.faq', compact('faqs'));
    }

    public function contacts(): View
    {
        return view('pages.contacts', ['contacts' => config('store')]);
    }

    public function about(): View
    {
        return view('pages.about', ['store' => config('store')]);
    }

    public function deals(): View
    {
        $products = Product::query()
            ->with(['images', 'brand', 'categories', 'stock', 'variants.stock'])
            ->active()
            ->where(function ($query): void {
                $query->whereColumn('compare_price', '>', 'price')
                    ->orWhereHas('variants', fn ($variant) => $variant->where('is_active', true)->whereColumn('product_variants.compare_price', '>', 'product_variants.price'));
            })
            ->orderByRaw('(compare_price - price) DESC')
            ->paginate(12);

        return view('pages.deals', compact('products'));
    }

    public function privacy(): View
    {
        return view('pages.privacy', ['store' => config('store')]);
    }

    public function terms(): View
    {
        return view('pages.terms', ['store' => config('store')]);
    }

    public function requisites(): View
    {
        return view('pages.requisites', ['store' => config('store')]);
    }

    public function contactSubmit(Request $request)
    {
        $key = 'contact|'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withInput()->withErrors(['contact' => 'Слишком много сообщений. Повторите попытку позже.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'privacy_consent' => ['accepted'],
            'website' => ['nullable', 'max:0'],
        ]);

        $recipient = config('store.email') ?: config('mail.from.address');
        if (! $recipient) {
            return back()->withInput()->withErrors(['contact' => 'Email магазина пока не настроен. Используйте телефон или мессенджер.']);
        }

        RateLimiter::hit($key, 3600);
        try {
            Mail::raw(
                "Имя: {$validated['name']}\nТелефон: {$validated['phone']}\nEmail: {$validated['email']}\nТема: {$validated['subject']}\n\n{$validated['message']}",
                fn ($message) => $message->to($recipient)->subject('Сообщение с сайта TechZone: '.$validated['subject'])
            );
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withInput()->withErrors(['contact' => 'Не удалось отправить сообщение. Попробуйте позже.']);
        }

        return back()->with('success', 'Сообщение отправлено.');
    }
}
