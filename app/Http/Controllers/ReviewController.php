<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $validator = Validator::make($request
            ->only(['review', 'rating']), [
            'review' => 'required|string',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->toJson()
            ], 400);
        }

        $review = new Review;
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->user_id = auth()->user()->id;

        $product->reviews()->save($review);
        return response()->json([
            'status' => true,
            'message' => 'Review successfully added',
            'review' => $review
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review = Review::find($id);
        if (auth()->user()->id !== $review->user_id) {
            return response()->json([
                'status' => false,
                'message' => 'Action forbidden'
            ]);
        }

        $validator = Validator::make($request
            ->only(['review', 'rating']), [
            'review' => 'string',
            'rating' => 'numeric|min:0|max:5',
        ]);

        // return response()->json(['message' => $validator->validated()]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->toJson()
            ], 400);
        }

        $review->update($validator->validated());
        return response()->json([
            'status' => true,
            'message' => 'Review successfully updated',
            'review' => $review
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review, Product $product)
    {
        if (auth()->user()->id !== $review->user_id) {
            return response()->json([
                'status' => false,
                'message' => 'Action forbidden'
            ]);
        }
        $review->delete();
        return response()->json(null, 204);
    }
}
