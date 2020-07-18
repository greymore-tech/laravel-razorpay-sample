@extends('layouts.app')

@section('content')
    <purchase razorpay_key="{{ $razorpay_key }}" order_id="{{ $order_id }}" product_id="{{ $product_id }}" product_name="{{ $product_name }}" product_price="{{ $product_price }}"></purchase>
@endsection
