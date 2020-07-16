@extends('layouts.app')

@section('content')
    <purchase razorpay_key="{{ $razorpay_key }}" order_id="{{ $order_id }}" product_id="{{ $product_id }}"></purchase>
@endsection
