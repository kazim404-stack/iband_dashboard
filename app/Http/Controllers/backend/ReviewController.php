<?php

namespace App\Http\Controllers\backend;

use App\DataTables\ReviewsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(ReviewsDataTable $dataTable)
    {
        return $dataTable->render('admin.review.index');
    }
    public function toggleReviewStatus(Review $review, $status)
    {
        $review->update([
            'approved' => $status
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully'
        ]);
    }
    public function delete(Review $review)
    {
        $review->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Review has been deleted successfully'
        ]);
    }
}
