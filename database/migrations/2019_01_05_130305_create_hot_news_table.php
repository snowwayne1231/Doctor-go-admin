<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hot_news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('content', 1020);
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
        Schema::dropIfExists('hot_news');
    }
}
