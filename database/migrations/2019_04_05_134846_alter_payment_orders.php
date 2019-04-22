<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_order_products', function (Blueprint $table) {
            $table->float('redeem', 15, 2);
            $table->float('total_net', 15, 2);
        });

        Schema::table('payment_orders', function (Blueprint $table) {
            $table->float('total_redeem', 15, 2);
            $table->float('total_net', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_order_products', function (Blueprint $table) {
            $table->dropColumn('redeem');
            $table->dropColumn('total_net');
        });

        Schema::table('payment_orders', function (Blueprint $table) {
            $table->dropColumn('total_redeem');
            $table->dropColumn('total_net');
        });
    }
}
