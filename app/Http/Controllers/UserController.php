<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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
            $user = User::find($id);
            return response()->json(['status' => true, 'user' => $user]);
        } else {
            return response()->json(['status' => false, 'message' => 'User does not exist']);
        }
    }

    public function myProducts()
    {
        return response()->json(['status' => true, 'products' => auth()->user()->products]);
        // return response()->json(['status' => true, 'products' => auth()->user()->products]);
    }

    public function userProducts($id)
    {
        $products = Product::where('user_id', $id)->get();
        if (count($products) > 0) {
            return response()->json(['status' => true, 'products' => $products]);
        } else {
            return response()->json(['status' => false, 'message' => 'User does not have any products']);
        }
    }
}
