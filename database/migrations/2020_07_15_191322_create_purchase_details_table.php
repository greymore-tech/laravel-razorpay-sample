<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_status', 10);
            $table->string('email', 150);
            $table->bigInteger('contact');
            $table->string('product_name', 200);
            $table->bigInteger('product_price');
            $table->string('razorpay_payment_id', 50);
            $table->string('razorpay_order_id', 50);
            $table->string('razorpay_signature', 100)->nullable();
            $table->string('razorpay_refund_id', 50)->nullable();
            $table->string('razorpay_refund_status', 50)->nullable();
            $table->mediumText('error')->nullable();
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_details');
    }
}
