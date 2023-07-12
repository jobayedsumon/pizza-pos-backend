<?php

namespace App\Http\Controllers;

use App\Model\Order;
use App\Model\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        if (session()->has('payment_method') == false) {
            session()->put('payment_method', 'ssl_commerz_payment');
        }

        $params = explode('&&', base64_decode($request['token']));
        foreach ($params as $param) {
            $data = explode('=', $param);
            if ($data[0] == 'customer_id') {
                session()->put('customer_id', $data[1]);
            } elseif ($data[0] == 'uuid') {
                $uuid = $data[1];
            } elseif ($data[0] == 'email') {
                session()->put('email', $data[1]);
            } elseif ($data[0] == 'phone') {
                session()->put('phone', $data[1]);
            } elseif ($data[0] == 'f_name') {
                session()->put('f_name', $data[1]);
            } elseif ($data[0] == 'l_name') {
                session()->put('l_name', $data[1]);
            } elseif ($data[0] == 'callback') {
                session()->put('callback', $data[1]);
            } elseif ($data[0] == 'order_amount') {
                session()->put('order_amount', $data[1]);
            } elseif ($data[0] == 'product_ids') {
                session()->put('product_ids', $data[1]);
            }
        }
        
        if(isset($uuid)){ // regular/logged-in user
        
            $user = User::query()->firstOrCreate([
                'email' => session('email'),
                'phone' => session('phone')
            ],[
                'f_name' => session('f_name'),
                'l_name' => session('l_name'),
                'email' => session('email'),
                'phone' => session('phone')
            ]);
            
            \DB::table('guest_order_uuids')->insert([
                'user_id' => $user->id,
                'order_uuid' => $uuid,
            ]);

            $customer = User::query()->find($user->id);
            
        } elseif (session()->has('customer_id')) { // guest user
            $customer = User::query()->firstWhere(['id' => session('customer_id'), 'is_active' => 1]);
        }
        
        $order_amount = session('order_amount');
        session()->put('customer_id',$customer->id);

        if (isset($customer) && isset($order_amount)) {
            $data = [
                'name' => $customer['f_name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
            ];
            session()->put('data', $data);
            return view('payment-view');
        }

        if (!isset($customer))
            return response()->json(['errors' => ['message' => 'Customer not found or Unauthenticated']], 403);
        elseif (!isset($order_amount))
            return response()->json(['errors' => ['message' => 'Amount not found']], 403);
        else
            return response()->json(['errors' => ['message' => '']], 403);

    }

    public function success()
    {
        if (session()->has('callback')) {
            return redirect(session('callback') . '/success');
        }
        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function fail()
    {
        if (session()->has('callback')) {
            return redirect(session('callback') . '/fail');
        }
        return response()->json(['message' => 'Payment failed'], 403);
    }
}

