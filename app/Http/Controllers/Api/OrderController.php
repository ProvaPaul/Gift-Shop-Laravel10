<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Orders of the authenticated user only
    public function index(Request $request)
    {
        return response()->json(
            Order::where('user_id', $request->user()->id)->latest()->paginate(10)
        );
    }

    public function show(Request $request, $id)
    {
        return response()->json(
            Order::with('items')->where('user_id', $request->user()->id)->findOrFail($id)
        );
    }
}
