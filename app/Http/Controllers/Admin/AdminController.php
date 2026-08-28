<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        return view('admin.dashboard', [
            'productsCount' => Product::count(),
            'activeProductsCount' => Product::where('is_active', true)->count(),
            'pendingOrdersCount' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'pendingReviewsCount' => Review::where('is_approved', false)->count(),
            'recentOrders' => Order::with('user')->latest()->limit(8)->get(),
        ]);
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->isAdmin(), 403);
    }
}
