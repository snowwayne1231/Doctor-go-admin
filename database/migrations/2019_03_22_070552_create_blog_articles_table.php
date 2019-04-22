<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_magazines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id')->nullable();
            $table->string('title', 255)->nullable();
            $table->text('image')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('store_id')->nullable();
            $table->integer('language_id')->default(1);
            
            $table->integer('sort')->default(1);
            $table->integer('status')->default(1);
            $table->date('public_date')->nullable();

            $table->boolean('allow_comment')->default(false);
            $table->integer('final_editor_id')->nullable();
            $table->timestamps();
        });

        Schema::create('blog_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('magazine_id')->nullable();
            $table->integer('author_id')->nullable();
            $table->text('image')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('store_id')->nullable();

            $table->string('related_method', 64)->nullable();
            $table->text('related_option')->nullable();
            $table->integer('sort')->default(1);
            $table->integer('status')->default(1);
            
            $table->date('public_date')->nullable();
            $table->boolean('allow_comment')->default(false);
            $table->integer('final_editor_id')->nullable();
            $table->timestamps();
        });

        Schema::create('blog_article_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id');
            $table->integer('language_id')->nullable()->default(1);;
            $table->string('title', 255)->nullable();
            $table->mediumText('description')->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('meta_keyword', 255)->nullable();

            $table->integer('final_editor_id')->nullable();
            $table->timestamps();
        });

        Schema::create('blog_article_authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->text('image')->nullable();
            $table->integer('status')->default(1);

            $table->integer('final_editor_id')->nullable();
            $table->timestamps();
        });

        Schema::create('blog_article_author_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id');
            $table->integer('language_id')->nullable()->default(1);;
            $table->text('description', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('meta_keyword', 255)->nullable();

            $table->integer('final_editor_id')->nullable();
            $table->timestamps();
        });

        Schema::create('blog_article_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->text('image')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('top_id')->nullable();
            $table->integer('column')->nullable();
            $table->integer('sort')->default(1);
            $table->integer('status')->default(1);

            $table->integer('final_editor_id')->nullable();
            $table->timestamps();
        });

        Schema::create('blog_article_category_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('language_id')->nullable()->default(1);
            $table->string('name', 255);
            $table->text('description', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('meta_keyword', 255)->nullable();

            $table->integer('final_editor_id')->nullable();
            $table->timestamps();
        });

        Schema::create('blog_article_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id');
            $table->integer('reply_id')->nullable();
            $table->integer('customer_id');
            $table->text('comment');
            $table->integer('status')->default(1);

            $table->timestamps();
        });

        Schema::create('blog_article_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id');
            $table->integer('view')->default(1);

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
        Schema::dropIfExists('blog_magazines');
        Schema::dropIfExists('blog_articles');
        Schema::dropIfExists('blog_article_descriptions');
        Schema::dropIfExists('blog_article_authors');
        Schema::dropIfExists('blog_article_author_descriptions');
        Schema::dropIfExists('blog_article_categories');
        Schema::dropIfExists('blog_article_category_descriptions');
        Schema::dropIfExists('blog_article_comments');
        Schema::dropIfExists('blog_article_views');
    }
}
