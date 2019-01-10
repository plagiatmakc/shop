<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CartImplementation;
use App\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $order = Order::create($request->all());

        return response()->json(['order_id' => $order->id]);
    }

    public function show($order_id)
    {
        $order = Order::findOrFail($order_id);
        if($order->user_id == Auth::id()) {
            return response()->json($order);
        }else {
            return response();
        }

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

}
