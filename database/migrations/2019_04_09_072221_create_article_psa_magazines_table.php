<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlePsaMagazinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_psa_magazines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('author', 255)->nullable();
            $table->string('title', 255)->default('');
            $table->text('image')->nullable();
            // $table->integer('category_id')->nullable();
            $table->integer('sort')->default(1);
            $table->boolean('enable')->default(true);
            $table->date('public_date')->nullable();

            $table->timestamps();
        });

        Schema::create('article_psa_chapters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('magazine_id')->nullable();
            $table->string('author', 255)->nullable();
            $table->string('title', 255)->default('');
            $table->text('image')->nullable();

            $table->integer('sort')->default(1);
            $table->boolean('enable')->default(true);
            $table->date('public_date')->nullable();

            $table->timestamps();
        });

        Schema::create('article_psa_chapter_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chapter_id')->nullable();
            
            $table->text('content')->nullable();

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
        Schema::dropIfExists('article_psa_magazines');
        Schema::dropIfExists('article_psa_chapters');
        Schema::dropIfExists('article_psa_chapter_descriptions');
    }
}
