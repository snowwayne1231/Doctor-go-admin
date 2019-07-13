<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupBuy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_group_buyings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('organizer', 255)->nullable();
            $table->integer('status')->default(1);
            $table->timestamp('time_start')->useCurrent = true;
            $table->timestamp('time_end')->nullable();
            $table->text('discount_json')->nullable();
            $table->integer('sum_quantity')->default(0);
            $table->integer('sum_order')->default(0);

            $table->integer('currency_id')->nullable();
            $table->float('total', 15, 2)->default(0.00);
            $table->float('total_net', 15, 2)->default(0.00);
            $table->float('total_redeem', 15, 2)->default(0.00);

            $table->timestamps();
        });


        Schema::create('payment_group_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_order_id');
            $table->integer('customer_id');
            $table->integer('status')->default(1);
            $table->integer('currency_id')->nullable();
            $table->integer('quantity');
            $table->float('final_price', 15, 2)->nullable();
            $table->float('total_net', 15, 2)->nullable();

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
        Schema::dropIfExists('product_group_buyings');
        Schema::dropIfExists('payment_group_orders');
    }
}
