<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_carts', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('customer_id')->index();
            $table->string('name', 32)->nullable();
            $table->text('json')->nullable();
            $table->integer('sort')->default(1);
            $table->string('ip')->nullable();

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
        Schema::dropIfExists('payment_carts');
    }
}
