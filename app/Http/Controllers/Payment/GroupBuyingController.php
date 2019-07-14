<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use App\Exceptions\AuthException;
use App\Models\PaymentGroupOrder;
use App\Models\ProductGroupBuying;

class GroupBuyingController extends BasicController
{
    public function get(Request $request) {
        $customer_id = $request->customer['id'];
        $result = PaymentGroupOrder::where('customer_id', $customer_id)->first();
        return $this->basicJSON($result);
    }

    public function create(Request $request) {

        $inputs = $this->validate($request, [
            'id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        $customer_id = $request->customer['id'];
        $ip = $request->ip();

        $product_group = ProductGroupBuying::find($inputs['id']);

        if (empty($product_group)) {
            throw new \Exception('Worng id.');
        }

        $order = new PaymentGroupOrder();
        $order->product_order_id = $inputs['id'];
        $order->quantity = $inputs['quantity'];
        $order->customer_id = $customer_id;
        
        $order->save();

        $product_group->increment('sum_quantity', $inputs['quantity']);
        $product_group->increment('sum_order', 1);

        return $this->basicJSON($product_group);
    }

}

