<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddEventImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_brands', function (Blueprint $table) {
            //
            $table->text('event_image')->nullable();
        });

        Schema::table('product_categories', function (Blueprint $table) {
            //
            $table->text('event_image')->nullable();
        });

        Schema::create('ad_events', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enable')->default(true);
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->string('link', 1020)->nullable();
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
        Schema::table('product_brands', function (Blueprint $table) {
            //
            $table->dropColumn('event_image');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            //
            $table->dropColumn('event_image');
        });

        Schema::dropIfExists('ad_events');
    }
}
