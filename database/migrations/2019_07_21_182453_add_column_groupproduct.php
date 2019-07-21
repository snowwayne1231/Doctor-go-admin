<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnGroupproduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_group_orders', function (Blueprint $table) {
            //
            $table->integer('product_id')->after('product_order_id');
            $table->index('product_id');
            $table->index('product_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_group_orders', function (Blueprint $table) {
            //
            $table->dropColumn('product_id');
            $table->dropIndex('product_id');
            $table->dropIndex('product_order_id');
        });
    }
}
