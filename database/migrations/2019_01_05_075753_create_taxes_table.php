<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 32);
            $table->string('description', 255)->nullable();
            $table->integer('zone_id')->nullable();
            $table->float('rate', 15, 4)->default(0);
            $table->string('type', 4)->nullable();
            $table->string('based', 16)->nullable();
            $table->integer('priority')->default(1);

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
        Schema::dropIfExists('taxes');
    }
}
