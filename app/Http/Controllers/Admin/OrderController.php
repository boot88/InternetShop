<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);
        $status = $request->string('status')->toString();
        $orders = Order::query()
            ->with('user')
            ->withCount('items')
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Request $request, Order $order)
    {
        $this->authorizeAdmin($request);
        $order->load(['user', 'items.product.images', 'histories' => fn ($query) => $query->latest()]);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled,refunded'],
            'payment_status' => ['required', 'in:pending,paid,failed'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($order, $data): void {
            if ($order->status === 'cancelled' && $data['status'] !== 'cancelled') {
                throw ValidationException::withMessages(['status' => 'Отменённый заказ нельзя вернуть в работу автоматически. Создайте новый заказ.']);
            }
            if ($data['status'] === 'cancelled' && ! in_array($order->status, ['pending', 'processing'], true)) {
                throw ValidationException::withMessages(['status' => 'Автоматическая отмена доступна только до передачи заказа в доставку.']);
            }

            $statusChanged = $order->status !== $data['status'];
            if ($data['status'] === 'cancelled' && ! $order->stock_restored_at) {
                $order->load('items');
                foreach ($order->items as $item) {
                    $stock = \App\Models\Stock::query()
                        ->when($item->variant_id, fn ($query) => $query->where('variant_id', $item->variant_id), fn ($query) => $query->where('product_id', $item->product_id)->whereNull('variant_id'))
                        ->lockForUpdate()
                        ->first();
                    $stock?->increment('quantity', $item->quantity);
                }
                $order->stock_restored_at = now();
            }
            $order->update([
                'status' => $data['status'],
                'payment_status' => $data['payment_status'],
                'stock_restored_at' => $order->stock_restored_at,
            ]);
            if ($statusChanged || ! empty($data['note'])) {
                $order->addHistory($data['status'], $data['note'] ?: null);
            }
        });

        return back()->with('success', 'Статус заказа обновлён.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->isAdmin(), 403);
    }
}
