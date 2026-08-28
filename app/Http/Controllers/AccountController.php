<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function edit(Request $request)
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        $emailChanged = $data['email'] !== $user->email;
        if ($emailChanged) {
            $data['email_verified_at'] = null;
        }
        $user->forceFill($data)->save();

        if ($emailChanged) {
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return back()->with('success', 'Данные профиля обновлены.');
    }

    public function orders(Request $request)
    {
        $orders = $request->user()->orders()->withCount('items')->latest()->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function order(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load(['items.product.images', 'histories' => fn ($query) => $query->latest()]);

        return view('account.order', compact('order'));
    }
}
