<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function delivery(): View
    {
        return view('pages.delivery', [
            'title' => 'Доставка и оплата',
            'breadcrumbs' => [
                ['name' => 'Главная', 'url' => '/'],
                ['name' => 'Доставка и оплата', 'url' => route('pages.delivery')]
            ]
        ]);
    }

    public function returns(): View
    {
        return view('pages.returns', [
            'title' => 'Возврат товара',
            'breadcrumbs' => [
                ['name' => 'Главная', 'url' => '/'],
                ['name' => 'Возврат товара', 'url' => route('pages.returns')]
            ]
        ]);
    }

    public function faq(): View
    {
        $faqs = [
            [
                'question' => 'Как оформить заказ?',
                'answer' => 'Выберите товары, добавьте их в корзину, перейдите в корзину и заполните данные для доставки. После подтверждения заказа мы свяжемся с вами для уточнения деталей.'
            ],
            [
                'question' => 'Сколько стоит доставка?',
                'answer' => 'Доставка бесплатна при заказе от 5000 рублей. При меньшей сумме стоимость доставки рассчитывается индивидуально в зависимости от вашего местоположения.'
            ],
            [
                'question' => 'Как долго обрабатывается заказ?',
                'answer' => 'Обработка заказа занимает от 1 до 24 часов. В выходные дни обработка может занять больше времени.'
            ],
            [
                'question' => 'Можно ли изменить адрес доставки?',
                'answer' => 'Да, вы можете изменить адрес доставки до момента отправки товара. Для этого свяжитесь с нашей службой поддержки.'
            ],
            [
                'question' => 'Какие способы оплаты принимаются?',
                'answer' => 'Мы принимаем банковские карты (Visa, MasterCard, Мир), электронные деньги (ЮMoney, Qiwi), а также наличные при получении.'
            ],
            [
                'question' => 'Есть ли гарантия на товары?',
                'answer' => 'Да, на все товары предоставляется гарантия от 1 года в зависимости от категории товара. Подробности смотрите в описании товара.'
            ]
        ];

        return view('pages.faq', compact('faqs'), [
            'title' => 'Частые вопросы',
            'breadcrumbs' => [
                ['name' => 'Главная', 'url' => '/'],
                ['name' => 'Частые вопросы', 'url' => route('pages.faq')]
            ]
        ]);
    }

    public function contacts(): View
    {
        $contacts = [
            'phone' => '+7 (999) 999-99-99',
            'email' => 'info@store.com',
            'address' => 'г. Москва, ул. Примерная, д. 1',
            'work_hours' => 'Пн-Пт: 9:00-18:00, Сб-Вс: 10:00-16:00'
        ];

        return view('pages.contacts', compact('contacts'), [
            'title' => 'Контакты',
            'breadcrumbs' => [
                ['name' => 'Главная', 'url' => '/'],
                ['name' => 'Контакты', 'url' => route('pages.contacts')]
            ]
        ]);
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);

        // Здесь можно добавить логику отправки email
        // Mail::to('info@store.com')->send(new ContactForm($validated));

        return redirect()->back()->with('success', 'Ваше сообщение успешно отправлено! Мы свяжемся с вами в ближайшее время.');
    }
}