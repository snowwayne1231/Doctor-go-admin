<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128);
            $table->string('type_name', 16);
            $table->string('code', 16)->nullable();
            $table->float('discount', 15, 4)->nullable();
            $table->float('discount_percentage', 8, 4)->nullable();
            $table->float('discount_limit', 15, 4)->nullable();
            $table->boolean('shipping')->default(false);
            $table->integer('total_used')->default(0);
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->integer('status')->default(1);
            $table->integer('final_editor_id')->nullable();

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
        Schema::dropIfExists('payment_coupons');
    }
}
