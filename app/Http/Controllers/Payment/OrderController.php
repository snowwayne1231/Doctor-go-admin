<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\BasicController;
use App\Http\Controllers\Customer\CustomerController;
use App\Exceptions\AuthException;
use App\Models\Product;
use App\Models\Address;
use App\Models\PaymentCart;
use App\Models\PaymentOrder;
use App\Models\PaymentOrderProduct;
use App\Models\SettingPromotion;
use App\Models\Customer;

class OrderController extends BasicController
{
    //
    public function create(Request $request) {
        $inputs = $this->validate($request, [
            'json' => 'required|json',
            'promotion' => 'required|numeric',
        ]);


        $buying = json_decode($inputs['json'], true);
        $customer = $request->customer;

        if (empty($customer['address'])) {
            
            $customer = CustomerController::updateCustomer($request, $customer);
        }

        if (
            empty($customer['address']['address_1']) ||
            empty($customer['address']['address_2']) ||
            empty($customer['address']['postcode'])
        ) {
            throw new \Exception('錯誤的地址 請完善地址資料');
        }

        $product_ids = [];
        foreach ($buying as $e) {
            $product_ids[] = $e['product'];
        }

        $order = new PaymentOrder();
        $order->customer_id = $customer['id'];
        $order->customer_name = $customer['firstname'] .' '. $customer['lastname'];
        $order->customer_email = $customer['email'];
        $order->customer_telephone = $customer['telephone'];
        $order->payment_address = $customer['address']['address_1'].$customer['address']['address_2'];
        $order->payment_city = $customer['address']['city'];
        $order->payment_postcode = $customer['address']['postcode'];
        $order->payment_country_id = $customer['address']['country_id'];
        // $order->language_id = 1;
        $order->ip = $request->ip();
        $order->promotion_id = $inputs['promotion'];

        if ($inputs['promotion'] > 0) {
            $promotion = SettingPromotion::find($inputs['promotion']);

            if (!$promotion) {
                throw new \Exception('錯誤的優惠代號');
            }

            $total_redeem = $promotion->redeem_point;
        } else {
            $total_redeem = 0;
        }
        
        $total_price = 0;
        $order_products = [];

        $products = Product::whereIn('id', $product_ids);
        $product_price_map = $products->pluck('price', 'id');
        // $product_discount_map = $products->pluck('point_can_be_discount', 'id');
        $product_quantity_map = $products->pluck('quantity', 'id');
        $point_reward_map = $products->pluck('point_reward', 'id');

        foreach ($buying as $e) {
            $id = $e['product'];
            $amount = $e['amount'];
            $redeem_point = $e['redeem'] ?? 0;

            if (!isset($product_price_map[$id])) { throw new \Exception('錯誤的產品代號'); }

            $price = $product_price_map[$id];
            // $discount = $product_discount_map[$id];
            $quantity = $product_quantity_map[$id];
            $reward = $point_reward_map[$id];

            if ($amount > $quantity) { throw new \Exception('存貨不足'); }

            // $tr = $discount * $redeem;
            $tr = $redeem_point;
            $tp = ($price * $amount);

            $total_price += $tp;
            $total_redeem += $tr;

            $order_products[] = [
                'product_id' => $id,
                'quantity' => $amount,
                'price' => $price,
                'redeem' => $tr,
                'total' => $tp,
                'total_net' => $tp - $tr,
                'reward' => $reward * $amount,
            ];
        }

        if ($total_redeem > $customer['point']) {
            throw new \Exception('紅利點數不足');
        }

        if ($total_price < 0) {
            throw new \Exception('錯誤的價格 請洽客服諮詢');
        }

        $order->total_redeem = round($total_redeem, 2);
        $order->total = round($total_price, 2);
        $order->total_net = round($total_price - $total_redeem, 2);
        $order->save();

        foreach ($order_products as &$order_product) {
            $order_product['order_id'] = $order->id;
            $product = Product::find($order_product['product_id']);
            $product->quantity = $product->quantity - $order_product['quantity'];
            $product->save();
        }
        

        $orderProduct = PaymentOrderProduct::insert($order_products);
        
        $customer['point'] -= $total_redeem;
        $next = CustomerController::updateCustomer($request, $customer);

        $cart = PaymentCart::firstOrNew([
            'customer_id' => $customer['id'],
        ]);

        $cart->json = '[]';
        $cart->save();

        $mail_address = env('MAIL_FROM_ADDRESS', null);

        if (isset($mail_address)) {
            \Mail::raw('有訂單剛剛完成下單 從 '.$request->ip().' 訂單時間: '. now(), function($message) use($mail_address) {
                $message->to($mail_address)->subject('美醫聯購 手機 APP 訂單下單');
            });
        }
        

        $result = [
            'order' => $order->toArray(),
            'customer' => $next,
        ];

        return $this->basicJSON($result);
    }

}

