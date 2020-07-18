<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('products', 'ProductController@index')->name('index');

Route::get('purchase/{product_id}', 'PurchaseController@index')->name('purchase');

Route::post('payment/verify/{product_id}', 'PurchaseController@paymentVerify')->name('payment');

Route::get('payment/{id}/refund/{payment_id}', 'PurchaseController@paymentRefund')->name('payment.refund');

Route::get('transactions', 'PurchaseController@transactionDetails')->name('transaction.details');
