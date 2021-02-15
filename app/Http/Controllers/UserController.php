<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::query()
            ->withCount('products', 'reviews')
            ->latest()
            ->paginate(20);
        return response()->json(['status' => true, 'users' => $users]);
    }

    public function show($id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::where('id', $id)->get();
            return response()->json(['status' => true, 'user' => $user[0]]);
        } else {
            return response()->json(['status' => false, 'message' => 'User does not exist']);
        }
    }

    public function myProducts()
    {
        if (Product::where('user_id', auth()->user()->id)->exists()) {
            $products = Product::where('user_id', auth()->user()->id)->get();
            return response()->json(['status' => true, 'user' => $products]);
        } else {
            return response()->json(['status' => false, 'message' => 'User does not have any products']);
        }
    }

    public function userProducts($id)
    {
        if (Product::where('user_id', $id)->exists()) {
            $products = Product::where('user_id', $id)->get();
            return response()->json(['status' => true, 'user' => $products]);
        } else {
            return response()->json(['status' => false, 'message' => 'User does not have any products']);
        }
    }
}
