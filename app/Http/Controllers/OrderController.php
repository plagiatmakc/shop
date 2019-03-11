<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CartImplementation;
use App\Order;
use Illuminate\Support\Facades\Auth;
use App\Payment;
use App\Notifications\ChangeOrderStatus;

class OrderController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('Admin')) {
            return response()->json(Order::with('user', 'payment')->paginate(10));
        } else {
            return response()->json(Auth::user()->orders()->with('user', 'payment')->paginate(10));
        }
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
        if (Auth::user()->hasRole('Admin')) {
            return response()->json($order->load('payment', 'user'));
        } else if ($order->user_id == Auth::id()) {
            return response()->json($order);
        }
        return response();

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

    public function changeStatus($order_id, Request $request)
    {
        $order = Order::findOrFail($order_id);
        $order->setStatus($request->status);
        return response()->json($order);
        //Notification via mail
//        $notify =$order
//                ->user
//                ->notify(new ChangeOrderStatus('Hi, you order change status to ' . Order::getStatusesList()[$order->status_id],
//            'http://shop.loc/dashboard/order/' . $order_id
//                ));
//        return response()->json($notify);
    }
}
