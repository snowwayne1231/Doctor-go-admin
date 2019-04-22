<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128);
            $table->string('iso_code_2', 2);
            $table->string('iso_code_3', 3);
            $table->text('address_format')->nullable();
            $table->tinyInteger('postcode_require')->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('countries');
    }
}
