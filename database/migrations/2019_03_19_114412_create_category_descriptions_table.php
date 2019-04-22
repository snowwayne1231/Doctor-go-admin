<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('language_id')->nullable();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('title', 255)->nullable();
            $table->string('meta', 255)->nullable();
            $table->string('keyword', 255)->nullable();
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
        Schema::dropIfExists('product_category_descriptions');
    }
}
