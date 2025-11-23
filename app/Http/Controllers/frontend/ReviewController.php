<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $exists = $this->checkIfUserAlreadyReviewedTheProduct($request->product_id, $request->user()->id);
        if ($exists) {
            return response()->json([
                "error" => "You have already reviewed this product"
            ]);
        } else {
            Review::create([
                'product_id' => $request->product_id,
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'body' => $request->body,
                'rating' => $request->rating,
            ]);
            return response()->json([
                'message' => 'Your review has been added successfully and will published soon'
            ]);
        }
    }
    public function update(Request $request)
    {
        $review = $this->checkIfUserAlreadyReviewedTheProduct($request->product_id, $request->user()->id);
        if ($review) {
            $review->update([
                'product_id' => $request->product_id,
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'body' => $request->body,
                'rating' => $request->rating,
                'approved' => 0
            ]);
            return response()->json([
                'message' => 'Your review has been updated successfully and will published soon'
            ]);
        } else {
            return response()->json(['error' => 'Somethng went wrong please try later']);
        }
    }

    public function checkIfUserAlreadyReviewedTheProduct($product_id, $user_id)
    {
        $review = Review::where([
            'product_id' => $product_id,
            'user_id' => $user_id
        ])->first();
        return $review;
    }
    public function delete(Request $request)
    {
        $review = $this->checkIfUserAlreadyReviewedTheProduct($request->product_id, $request->user()->id);
        if ($review) {
            $review->delete();
            return response()->json([
                'message' => 'Your review has been deleted successfully'
            ]);
        } else {
            return response()->json([
                'error' => "something went wrong please try later"
            ]);
        }
    }
}
