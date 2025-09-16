<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product','user')->orderBy('created_at','desc')->paginate(40);
        return request()->wantsJson() ? response()->json($reviews) : view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        return request()->wantsJson() ? response()->json($review) : view('admin.reviews.show', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'approved' => 'required|boolean',
            'rating' => 'nullable|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);

        $review->update($data);
        return $request->wantsJson() ? response()->json($review) : redirect()->back()->with('success','Review updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return request()->wantsJson() ? response()->json(['message'=>'deleted']) : redirect()->back()->with('success','Review deleted.');
    }
}
