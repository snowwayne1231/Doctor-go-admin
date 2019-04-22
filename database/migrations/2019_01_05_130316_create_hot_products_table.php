<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hot_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('product_option_id')->nullable();
            $table->string('title', 255)->nullable();
            $table->string('content', 1020)->nullable();
            $table->integer('sort')->defalut(1);
            $table->boolean('enable')->defalut(true);

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
        Schema::dropIfExists('hot_products');
    }
}
