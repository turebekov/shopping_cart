<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;
use Psy\Util\Str;
use Stripe\Charge;
use Stripe\Stripe;

class ProductController extends Controller
{
    public function index()
    {
       /* $a = [1,3,2,5,-4,56,7,80,9,10,11];


        for ($i=0; $i<count($a); $i++)
        {
            for ($j=1; $j<count($a);$j++)
            {
                if ($a[$i] > $a[$j])
                {
                    $b[$i] = $a[$j];
                }
            }
        }

        dump($b);*/

        $products = Product::all();
        return view('shop.index')->with(compact('products'));
    }

    public function getProfile()
    {
      $orders = Auth::user()->orders;
        $orders->transform(function ($order, $key){
            $order->cart = unserialize($order->cart);
            return $order;
        });
        return view('user.profile')->with(compact('orders'));
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $product->id);

        $request->session()->put('cart', $cart);
        return redirect()->action('ProductController@index');
    }
    public function getReduceByOne($id){
        $oldCart = Session::has('cart') ? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->reduceByOne($id);

        if (count($cart->items) >0){
            Session::put('cart',$cart);
        } else{
            Session::forget('cart');
        }
        return redirect()->action('ProductController@getCart');
    }
    public function getRemoveItem($id){
        $oldCart = Session::has('cart') ? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);

        if (count($cart->items) >0){
            Session::put('cart',$cart);
        } else{
            Session::forget('cart');
        }

        return redirect()->action('ProductController@getCart');
    }

    public function getCart()
    {
        if (!Session::has('cart')) {
            return view('shop.shopping-cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return view('shop.shopping-cart', ['products' => $cart->items,
            'totalPrice' => $cart->totalPrice]);
    }

    public function checkout()
    {
        if (!Session::has('cart')) {
            return view('shop.shopping-cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $total = $cart->totalPrice;

        return view('shop.checkout')->with(compact('total'));
    }

    public function postCheckout(Request $request)
    {
        if (!Session::has('cart')) {
            return redirect()->action('ProductController@getCart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        $order = new Order();
        $order->cart = serialize($cart);
        $order->address = $request->address;
        $order->name = $request->name;
        $order->tel = $request->tel;
        $order->total = $cart->totalPrice;

        Auth::user()->orders()->save($order);
        Stripe::setApiKey('pk_test_TMMdtoELapSoZcHNL5kcJgph');
        try {
            Charge::create(array(
                "amount" => $cart->totalPrice,
                "currency" => "usd",
                "source" => "$request->input('stripeToken')", // obtained with Stripe.js
                "description" => "Test Charge"

            ));
        } catch (\Exception $e) {
            return redirect()->route('checkout')->with('error',$e->getMessage());
        }
        Session::forget('cart');
        return redirect()->route('product')->with('success','Successfully purchased products');
    }
    public function getForget(){
        Session::forget('cart');
        return redirect()->route('product')->with('success','Korzina ochisten');
    }

}
