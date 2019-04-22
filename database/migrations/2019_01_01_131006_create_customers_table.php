<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->nullable();
            $table->integer('store_id')->nullable();
            // $table->integer('address_id')->nullable();
            $table->integer('api_key_id')->nullable();

            $table->string('email', 96);
            $table->string('account', 64)->nullable();
            $table->string('password', 64)->nullable();
            $table->string('avatar_url', 510)->nullable();

            $table->string('firstname', 32)->nullable();
            $table->string('lastname', 32)->nullable();
            
            $table->string('telephone', 32)->nullable();
            $table->string('fax', 32)->nullable();
            
            $table->string('salt', 12)->nullable();
            $table->integer('point')->default(0);
            $table->text('cart')->nullable();
            $table->text('watchlist')->nullable();
            $table->integer('newsletter')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('approved')->default(1);
            $table->tinyInteger('safe')->default(1);
            $table->text('custom_field')->nullable();
            $table->text('token')->nullable();
            $table->string('code', 40)->nullable();
            $table->string('doctor_profile', 128)->nullable();
            $table->string('doctor_clinic', 128)->nullable();
            $table->integer('doctor_profile_image_id')->nullable();
            $table->integer('doctor_clinic_image_id')->nullable();
            
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
        Schema::dropIfExists('customers');
    }
}
