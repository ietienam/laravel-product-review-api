<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('user:id,name')
            ->withCount('reviews')
            ->latest()
            ->paginate(20);
        return response()->json(['status' => true, 'products' => $products]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request
            ->only(['name', 'description', 'price']), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->toJson()
            ], 400);
        }

        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        auth()->user()->products()->save($product);
        return response()->json([
            'status' => true,
            'message' => 'Product successfully added',
            'product' => $product
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load(['reviews' => function ($query) {
            $query->latest();
        }, 'user']);
        return response()->json([
            'status' => true,
            'message' => 'Product successfully fetched',
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (auth()->user()->id !== $product->user_id) {
            return response()->json([
                'status' => false,
                'message' => 'Action forbidden'
            ]);
        }

        $validator = Validator::make($request
            ->only(['name', 'description', 'price']), [
            'name' => 'string',
            'description' => 'string',
            'price' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->toJson()
            ], 400);
        }

        $product->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Product successfully updated',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (auth()->user()->id !== $product->user_id) {
            return response()->json([
                'status' => false,
                'message' => 'Action forbidden'
            ]);
        }
        $product->delete();
        return response()->json(null, 204);
    }
}
