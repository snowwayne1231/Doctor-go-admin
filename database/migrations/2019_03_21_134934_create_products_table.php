<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->nullable();
            $table->integer('status')->default(1);
            $table->text('image')->nullable();
            $table->text('image_detail')->nullable();
            
            $table->integer('quantity')->default(1);
            $table->integer('stock_status')->default(1);
            
            $table->float('price', 15, 2);
            $table->float('price_forshow', 15, 2)->nullable();

            $table->integer('point_reward')->nullable();
            $table->integer('point_can_be_discount')->nullable();

            $table->integer('sort')->default(1);
            $table->boolean('is_shipping')->default(false);

            $table->integer('store_id')->nullable();
            $table->integer('manufacturer_id')->nullable();

            $table->integer('tax_id')->default(1);
            $table->integer('viewed')->default(1);
            
            $table->string('model', 64)->nullable();
            $table->string('sku', 64)->nullable();
            $table->string('location', 128)->nullable();

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
        Schema::dropIfExists('products');
    }
}
