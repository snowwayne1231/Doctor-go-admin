<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiDivicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_divices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable();
            $table->string('uuid', 255)->index();
            $table->string('token', 64);
            $table->string('last_login_ip', 64)->nullable();
            $table->string('last_login_location', 128)->nullable();
            $table->dateTimeTz('last_login_datetime')->nullable();
            $table->string('option', 255)->nullable();
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
        Schema::dropIfExists('api_divices');
    }
}
