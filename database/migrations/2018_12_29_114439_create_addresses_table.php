<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->index();
            $table->integer('country_id');
            $table->integer('zone_id')->nullable();

            $table->string('first_name', 32)->nullable();
            $table->string('last_name', 32)->nullable();
            $table->string('company', 128)->nullable();
            $table->string('address_1', 128)->nullable();
            $table->string('address_2', 128)->nullable();
            $table->string('city', 128)->nullable();
            $table->string('postcode', 16)->nullable();
            $table->text('custom_field')->nullable();

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
        Schema::dropIfExists('addresses');
    }
}
