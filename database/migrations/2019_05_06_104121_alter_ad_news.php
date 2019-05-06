<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_news', function (Blueprint $table) {
            //
            $table->integer('link_type')->nullable();
            $table->string('link_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_news', function (Blueprint $table) {
            //
            $talbe->dropColumn('link_type');
            $talbe->dropColumn('link_value');
        });
    }
}
