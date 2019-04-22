<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdPointBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_point_banners', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enable')->default(true);
            $table->string('name', 255)->nullable();
            $table->string('description', 1020)->nullable();
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
        Schema::dropIfExists('ad_point_banners');
    }
}
