<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeoLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->nullable();
            $table->string('name', 32);
            $table->string('address', 255)->nullable();
            $table->string('telephone', 32)->nullable();
            $table->string('geocode', 32)->nullable();
            $table->string('open', 255)->nullable();
            $table->string('comment', 255)->nullable();
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
        Schema::dropIfExists('geo_locations');
    }
}
