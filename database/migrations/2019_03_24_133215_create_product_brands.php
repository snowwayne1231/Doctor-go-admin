<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBrands extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_brands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->default('');
            $table->string('description', 1020)->nullable();
            $table->text('image')->nullable();
            $table->boolean('enable')->default(true);
            $table->integer('sort')->default(1);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('brand_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_brands');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });
    }
}
