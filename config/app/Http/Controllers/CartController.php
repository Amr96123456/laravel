<?php

namespace App\Http\Controllers;
use App\Models\Order;
 use App\Models\OrderItem;
 use App\Models\transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->contenet();
        return view('cart',compact('items'));
    }
    public function add_to_cart(request $request)
    {
        Cart::instance('cart')-add($request->id, $request->name , $request->quantity , $request->id, associate('App\Models\Product'));
        return redirect()->back();
    }
    public function increase_cart_quantity($rowId)
    {
    $qty = $product->qty +1 ;
    Cart::instance('cart')->update($rowId,$qty);
    return  redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')($rowId);
        $qty = $product->qty -1 ;
        Cart::instance('cart')->update($rowId,$qty);
        return  redirect()->back();
    }
    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return  redirect()->back();
    }
    public function empty_Cart()
    {
        Cart::instance('cart')->destroy();
        return  redirect()->back();
    }
    public function apply_coupom_code(request $request)

    {
        $coupon_code = $request->coupon_code;
        if(isset($coupon_code))
        {
            $coupon = Coupon::where('code',$cupon_code)->where('expiry_date','>=',Carbon::today())
            ->where('cart_value','<=',Cart::instance('cart')->subtotal())->first();
            if($coupon)
            {
                Session::put('coupon',[
                    'code'=>$coupon->code,
                    'type'=>$coupon->code,
                    'value'=>$coupon->code,
                    'cart_value'=>$coupon->code,
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('error','Invalid coupon code!');
            }
        }
        else{
            return redirect()->back()->with('error','Invalid coupon code!');
        }
    }
    public function calculateDiscount()
    {
        $discount =0;
        if(Session::has('coupon'));
        {
            if(Session::get('coupon')['type']=='fixed');
            {
                $discount =Session::get('coupon')['value'];
            }
            else{
                $discount=(Cart::instance('cart')->subtotal() * Session::get('coupon')['value'])/100;
            }
            $subtotalAfterDiscount =Cart::instance('cart')->subtotal() - $discount;
            $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax'))/100;
            $totalAfterdiscount = $subtotalAfterDiscount + $taxAfterDiscount;

            Session::put('discounts',[
                'discount'=>mumber_foramt(floatval($discount),2,'-','-')
                'subtotal'=>mumber_foramt(floatval($subtotalAfterDiscount),2,'-','-')
                'tax'=>mumber_foramt(floatval($taxAfterDiscount),2,'-','-')
                'total'=>mumber_foramt(floatval($taxAfterDiscount),2,'-','-')
            ]);
        }
    }
    public function  remove_coupon_code()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return back()->with('success','Coupon has been removed')
    }

    public function checkout()
    {
        if(!Auth::check())
        {
            return redirect()->route('login');
        }
        $address = Address::where('user_id',Auth::user()->id)->where('isdefault',1)->first();
        return view('checkout',compact('address'))
    }
    public function place_an_order(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id',$user_id)->where('isdefault',true)->first();

        if(!$address)
        {
            $request->validate([
                'name'=>'required|max:100',
                'phone'=>'required|numeric|digits:10',
                'zip'=>'required|numeric|digits:10'
                'locality'=>'required',
                'address'=>'required',
                'city'=>'required',
                'state'=>'required',
                'country'=>'required',
                'landmark'=>'required',
            ]);
            $address = new Adress();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->locality = $request->locality;
            $address->address = $request->address;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->country = $request->country;
            $address->landmark = $request->clandmarkty;
            $address->country = ' ';
            $address->user_id = $user_id;
            $address->isdefault = true;
            $address->save()

            $this->setAmountforCheckout();

        }

            $order = new Order();
            $order->user_id = $user_id ;
            $order->subtotal = Session::get('checkout')['subtotal'];
            $order->discoun= Session::get('checkout')['discoun'];
            $order->tax =  Session::get('checkout')['tax'];
            $order->total =  Session::get('checkout')['total'];
            $order->name= $address->name;
            $order->phone = $address->phone;
            $order->locality= $address->locality;
            $order->address= $address->address;
            $order->city = $address->city;
            $order->state = $address->state;
            $order->country= $address->country;
            $order->landmark= $address->landmark;
            $order->zip= $address->zip;
            $orader->save();
            foreach (Cart::instance('cart')->content() as $item)
          {
            $orderItem  = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price_id = $item->price;
            $orderItem->quantity_id = $item->qty;
            $orderItem->save();
            }
            if($request->mode== "card")
            {
                //
            }
            elseif($request->mode == "paypal")
            {
                //
            }
            elseif($request->mode == "cod")

{
            $transaction = new transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order_id;
            $transaction->mode = $request->mode;
            $transaction->status = 'pending';
            $transaction->save();

            Cart::instance('cart')->destory();
            Session::forget('checkout');
            Session::forget('coupon');
            Session::forget('discounts');
            Session::put('order_id',$order_id);
            return::view('order_confirmation');
}
    }

    public function setAmountforCheckout()
    {
        if(!Cart::instance('cart')->content()->count()>0)
        {
        Session::forget('checkout')
        return ;
        }

        if(Session::has('coupon'))
        {
            Session::put('checkout',[
                'discount'=>Sesson::get('discount')['discount'],
                'subtotal'=>Sesson::get('discount')['subtotal'],
                'tax'=>Sesson::get('discount')['ditaxscount'],
                'total'=>Sesson::get('discount')['total'],
            ]);

        }
        else{
            Session::put('checkout',[
                'discount'=>0,
                'subtotal'=>Cart::instance('cart')subtotal();
                'tax'=>>Cart::instance('cart')tax();
                'total'=>>Cart::instance('cart')total();
            ]);
        }
    }


}
 public function order_confirmation()

 {
    if(Session::has('order_id'));
    {
        $order = Order::find(Session::get('order_id'));
        return view('order_confirmation',compact('order');)
    }
    return redirect()->route('cart.index');
 }
}




