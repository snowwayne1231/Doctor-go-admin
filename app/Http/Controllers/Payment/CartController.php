<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use App\Exceptions\AuthException;
use App\Models\PaymentCart;
// use App\Models\WatchlistArticle;

class CartController extends BasicController
{
    public function get(Request $request) {
        $customer_id = $request->customer['id'];
        $result = PaymentCart::where('customer_id', $customer_id)->first();
        return $this->basicJSON($result);
    }

    public function upsert(Request $request) {

        $inputs = $this->validate($request, [
            'json' => 'required|json',
        ]);

        $customer_id = $request->customer['id'];
        $ip = $request->ip();

        $cart = PaymentCart::firstOrNew([
            // 'json' => $inputs['json'],
            'customer_id' => $customer_id,
            // 'ip' => $ip,
        ]);

        $cart->json = $inputs['json'];
        $cart->ip = $ip;
        
        $cart->save();

        return $this->basicJSON($cart);
    }

}

