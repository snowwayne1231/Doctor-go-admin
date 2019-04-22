<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAdNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_news', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->default(1);
            $table->string('title', 255)->nullable();
            $table->string('headline', 255)->nullable();
            $table->string('content', 1020)->nullable();
            $table->integer('sort')->default(1);
            $table->boolean('enable')->default(true);
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
        Schema::dropIfExists('ad_news');
    }
}
