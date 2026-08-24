<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function delivery(): View
    {
        return view('pages.delivery', [
            'title' => 'Доставка и оплата',
            'breadcrumbs' => [
                ['name' => 'Главная', 'url' => '/'],
                ['name' => 'Доставка и оплата', 'url' => route('delivery')]
            ]
        ]);
    }

    public function returns(): View
    {
        return view('pages.returns', [
            'title' => 'Возврат товара',
            'breadcrumbs' => [
                ['name' => 'Главная', 'url' => '/'],
                ['name' => 'Возврат товара', 'url' => route('returns')]
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
                ['name' => 'Частые вопросы', 'url' => route('faq')]
            ]
        ]);
    }

    public function contacts(): View
    {
        $contacts = [
            'phone' => config('store.phone'),
            'email' => config('store.email'),
            'address' => config('store.address'),
            'work_hours' => config('store.hours'),
        ];

        return view('pages.contacts', compact('contacts'), [
            'title' => 'Контакты',
            'breadcrumbs' => [
                ['name' => 'Главная', 'url' => '/'],
                ['name' => 'Контакты', 'url' => route('contacts')]
            ]
        ]);
    }

    public function about(): View
    {
        return view('pages.about', ['store' => config('store')]);
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

        $recipient = config('mail.to.address') ?: config('mail.from.address');

        try {
            Mail::raw(
                "Имя: {$validated['name']}\nТелефон: {$validated['phone']}\nEmail: {$validated['email']}\nТема: {$validated['subject']}\n\n{$validated['message']}",
                fn ($message) => $message->to($recipient)->subject('Сообщение с сайта TechZone: '.$validated['subject'])
            );
        } catch (\Throwable $exception) {
            report($exception);
            Log::warning('Не удалось отправить сообщение с формы контактов.', ['email' => $validated['email']]);

            return back()->withInput()->withErrors(['contact' => 'Не удалось отправить сообщение. Попробуйте позже или свяжитесь с нами по телефону.']);
        }

        return redirect()->back()->with('success', 'Сообщение отправлено. Мы свяжемся с вами в ближайшее время.');
    }
	
	public function deals()
	{
		return view('pages.deals');
	}
	
}
