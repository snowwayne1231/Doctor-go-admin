<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_banners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->string('name', 64);
            $table->string('title', 64)->nullable();
            $table->text('description')->nullable();
            $table->string('link', 255)->nullable();
            $table->integer('sort')->default(1);
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
        Schema::dropIfExists('ad_banners');
    }
}
