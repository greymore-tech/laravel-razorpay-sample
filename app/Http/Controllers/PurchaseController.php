<?php

namespace App\Http\Controllers;

use App\Purchase;
use Razorpay\Api\Api;
use Illuminate\Http\Request;
use Illuminate\Http\Requests;
use App\Http\Resources\PurchaseResource;

class PurchaseController extends Controller
{
    public function index($product_id)
    {
        $products = array(
            array('id' => '1', 'name' => 'Product 1', 'description' => 'Product 1 Description', 'price' => '10'),
            array('id' => '2', 'name' => 'Product 2', 'description' => 'Product 2 Description', 'price' => '20'),
            array('id' => '3', 'name' => 'Product 3', 'description' => 'Product 3 Description', 'price' => '30'),
        );

        foreach ($products as $product) {
            if ($product['id'] == $product_id) {
                $product_id = $product['id'];
                $product_name = $product['name'];
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

        return view('purchase_page', compact('razorpay_key', 'order_id', 'product_id', 'product_name', 'product_price'));
    }

    public function paymentVerify(Request $request, $product_id)
    {
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

        if ($request->error == null) {
            //  razorpay api
            $razorpay_api = new Api(config('razorpay.razorpay_key'), config('razorpay.razorpay_secret'));
            $verify = array('razorpay_signature'  => $request->razorpay_signature,  'razorpay_payment_id'  => $request->razorpay_payment_id,  'razorpay_order_id' => $request->razorpay_order_id);
            $order = $razorpay_api->utility->verifyPaymentSignature($verify);

            $order_details = $razorpay_api->order->fetch($request->razorpay_order_id);

            $payment = $razorpay_api->payment->fetch($request->razorpay_payment_id)->capture(array('amount' => $order_details->amount, 'currency' => $order_details->currency));

            $purchase = new Purchase;
            $purchase->transaction_status = "Success";
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
            $razorpay_api = new Api(config('razorpay.razorpay_key'), config('razorpay.razorpay_secret'));
            $payment = $razorpay_api->payment->fetch(json_decode($request->error['metadata'])->payment_id);

            $purchase = new Purchase;
            $purchase->transaction_status = "Failed";
            $purchase->email = $payment->email;
            $purchase->contact = $payment->contact;
            $purchase->product_name = $product_name;
            $purchase->product_price = $product_price;
            $purchase->razorpay_payment_id = json_decode($request->error['metadata'])->payment_id;
            $purchase->razorpay_order_id = json_decode($request->error['metadata'])->order_id;
            $purchase->error = json_encode($request->error);
            $purchase->save();

            return redirect()->intended('/')->with('error', 'Payment Failed');
        }
    }

    public function paymentRefund($id, $payment_id)
    {
        $razorpay_api = new Api(config('razorpay.razorpay_key'), config('razorpay.razorpay_secret'));
        $payment = $razorpay_api->payment->fetch($payment_id);
        $refund = $payment->refund(array('speed' => "optimum"));

        $purchase = Purchase::find($id);
        $purchase->transaction_status = "Refund";
        $purchase->razorpay_refund_id = $refund->id;
        $purchase->razorpay_refund_status = $refund->status;
        $purchase->save();

        return redirect()->back();
    }

    public function transactionDetails()
    {
        return view('transaction_details');
    }

    public function transactionDetailsShowAll()
    {
        //  fetch all purchase
        $purchase = Purchase::paginate(5);

        //  return purchase as a resource
        return PurchaseResource::collection($purchase);
    }

    public function transactionDetailsShow($id)
    {
        //  fetch purchase with the id
        $purchase = Purchase::all()->where('id', $id);

        //  return purchase as a resource
        return PurchaseResource::collection($purchase);
    }
}
