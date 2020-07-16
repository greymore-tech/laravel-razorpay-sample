<?php

namespace App\Http\Controllers;

use App\Purchase;
use Razorpay\Api\Api;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index($product_id) {
        $products = array(
            array('id' => '1', 'name' => 'Product 1', 'description' => 'Product 1 Description', 'price' => '10'),
            array('id' => '2', 'name' => 'Product 2', 'description' => 'Product 2 Description', 'price' => '20'),
            array('id' => '3', 'name' => 'Product 3', 'description' => 'Product 3 Description', 'price' => '30'),
        );

        foreach ($products as $product) {
            if ($product['id'] == $product_id) {
                $product_price = $product['price'];
            }
        }

        //  razorpay api
        $razorpay_api = new Api(config('razorpay.razorpay_key'), config('razorpay.razorpay_secret'));

        //  creating order
        $order  = $razorpay_api->order->create([
            'amount'   =>  $product_price * 100,   //  paise to rupees
            'currency' =>  'INR'                   //  country currency
        ]);

        $razorpay_key = config("razorpay.razorpay_key");
        $order_id = $order['id'];

        return view('purchase_page', compact('razorpay_key', 'order_id', 'product_id'));
    }

    public function paymentVerify(Request $request, $product_id) {
        if ($request->error == null) {
            //  razorpay api
            $razorpay_api = new Api(config('razorpay.razorpay_key'), config('razorpay.razorpay_secret'));
            $verify = array('razorpay_signature'  => $request->razorpay_signature,  'razorpay_payment_id'  => $request->razorpay_payment_id,  'razorpay_order_id' => $request->razorpay_order_id);
            $order = $razorpay_api->utility->verifyPaymentSignature($verify);

            $order_details = $razorpay_api->order->fetch($request->razorpay_order_id);

            $payment = $razorpay_api->payment->fetch($request->razorpay_payment_id)->capture(array('amount' => $order_details->amount, 'currency' => $order_details->currency));

            $products = array(
                array('id' => '1', 'name' => 'Product 1', 'description' => 'Product 1 Description', 'price' => '10'),
                array('id' => '2', 'name' => 'Product 2', 'description' => 'Product 2 Description', 'price' => '20'),
                array('id' => '3', 'name' => 'Product 3', 'description' => 'Product 3 Description', 'price' => '30'),
            );

            foreach ($products as $product) {
                if ($product['id'] == $product_id) {
                    $product_name = $product['name'];
                    $product_price = $product['price'];
                }
            }

            // storing purchase related data in database
            $purchase = new Purchase;
            $purchase->email = $payment->email;
            $purchase->contact = $payment->contact;
            $purchase->product_name = $product_name;
            $purchase->product_price = $product_price;
            $purchase->razorpay_payment_id = $request->razorpay_payment_id;
            $purchase->razorpay_order_id = $request->razorpay_order_id;
            $purchase->razorpay_signature = $request->razorpay_signature;
            $purchase->save();

            return redirect()->intended('/')->with('success', 'Payment Successful');
        } else {
            return redirect()->intended('/')->with('error', 'Payment Failed');
        }
    }
}
