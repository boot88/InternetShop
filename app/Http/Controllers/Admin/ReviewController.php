<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);
        $reviews = Review::with(['product', 'user'])->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function update(Request $request, Review $review)
    {
        $this->authorizeAdmin($request);
        $review->update(['is_approved' => $request->boolean('is_approved')]);

        return back()->with('success', 'Статус отзыва обновлён.');
    }

    public function destroy(Request $request, Review $review)
    {
        $this->authorizeAdmin($request);
        $review->delete();

        return back()->with('success', 'Отзыв удалён.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->isAdmin(), 403);
    }
}
