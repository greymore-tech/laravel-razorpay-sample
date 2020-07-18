<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_status' => $this->transaction_status,
            'email' => $this->email,
            'contact' => $this->contact,
            'product_name' => $this->product_name,
            'product_price' => $this->product_price,
            'razorpay_payment_id' => $this->razorpay_payment_id,
            'razorpay_order_id' => $this->razorpay_order_id,
            'razorpay_refund_id' => $this->razorpay_refund_id,
            'razorpay_refund_status' => $this->razorpay_refund_status,
            'error' => json_decode($this->error)
        ];
    }
}
