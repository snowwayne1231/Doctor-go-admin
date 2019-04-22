<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_no')->nullable();
            $table->string('invoice_prefix', 32)->nullable();
            $table->integer('store_id')->nullable();
            $table->string('store_name', 64)->nullable();
            $table->string('store_url', 255)->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('customer_group_id')->nullable();
            $table->string('customer_name', 64)->nullable();
            $table->string('customer_email', 96)->nullable();
            $table->string('customer_telephone', 32)->nullable();
            $table->string('customer_tax', 32)->nullable();
            
            $table->string('payment_name', 64)->nullable();
            $table->string('payment_address', 255)->nullable();
            $table->string('payment_city', 128)->nullable();
            $table->string('payment_postcode', 10)->nullable();
            $table->integer('payment_country_id')->nullable();
            $table->integer('payment_zone_id')->nullable();
            $table->string('payment_method', 128)->nullable();
            $table->string('payment_code', 128)->nullable();
            
            $table->string('company', 64)->nullable();
            $table->string('affiliate', 64)->nullable();

            $table->string('shipping_name', 64)->nullable();
            $table->string('shipping_address', 255)->nullable();
            $table->string('shipping_city', 128)->nullable();
            $table->string('shipping_postcode', 10)->nullable();
            $table->integer('shipping_country_id')->nullable();
            $table->integer('shipping_zone_id')->nullable();
            $table->string('shipping_method', 128)->nullable();
            $table->string('shipping_code', 128)->nullable();

            $table->text('comment')->nullable();
            $table->float('total', 15, 2);
            $table->float('commission', 15, 2)->nullable();
            $table->integer('status')->default(1);
            $table->string('tracking', 64)->nullable();
            $table->integer('language_id')->nullable();
            $table->integer('currency_id')->nullable();
            $table->string('ip', 40)->nullable();
            $table->string('user_agent', 255)->nullable();
            
            $table->timestamps();
        });

        Schema::create('payment_order_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('order_status')->default(1);
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_order_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('product_id');
            $table->integer('quantity')->default(1);
            $table->float('price', 15, 2);
            $table->float('tax', 15, 2)->default(0);
            $table->float('other', 15, 2)->default(0);
            $table->float('total', 15, 2);
            $table->integer('reward')->nullable();

            $table->timestamps();
        });

        Schema::create('payment_order_totals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('code', 32)->nullable();
            $table->string('title', 255)->nullable();
            $table->float('value', 15, 2);
            $table->integer('sort')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_orders');
        Schema::dropIfExists('payment_order_histories');
        Schema::dropIfExists('payment_order_products');
        Schema::dropIfExists('payment_order_totals');
    }
}
