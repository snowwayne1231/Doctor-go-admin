<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('language_id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->text('tag')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 1020)->nullable();
            $table->string('meta_keyword', 255)->nullable();

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
        Schema::dropIfExists('product_descriptions');
    }
}
