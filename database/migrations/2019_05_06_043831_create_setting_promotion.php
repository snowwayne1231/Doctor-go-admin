<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingPromotion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->boolean('enable')->default(true);
            $table->integer('condition')->default(0);
            $table->integer('redeem_point')->default(0);
            $table->integer('redeem_cash')->default(0);

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
        Schema::dropIfExists('setting_promotions');
    }
}
