<?php

namespace App\Http\Controllers;
 use App\Models\Order;
 use App\Models\OrderItem;
 use App\Models\transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Facades\Auth;

class usercontroller extends Controller
{

    public function index()
    {
        return view("user.index");
    }
    public function orders()
    {
     $orders = Order::where('user_id',Auth::user()->id)->orderBy('create_at','DESC')->pagination(10);
     return view ('user.orders',compact('orders'));
    }
    public function  order_details($order_id)
    {
        $order = Order::where('user_id',Auth::user()->id)->where('id',$order_id)->first();
        if($order)
        {


        $orderItems = OrderItem::where('order_id',$order_id)->orderBy('id')->pagination(12);
        $transaction = transaction::where('order_id',$order_id)->first();
        return view('user.order_detais',compact('order','OrderItem','transaction'));
    }
    else{
        return redirect()->route('login');
    }
}
    public function order_cancel(Request $request)
{
        $order = order::find($request->order_id);
        $order->dtatus = 'canceled';
        $order-$canceled_date =  Carbon::now();
        return back()->with('status', 'Order has been canceled successfully!');
}
}
