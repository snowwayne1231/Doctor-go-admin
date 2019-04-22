<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            //
            $table->string('key', 255)->nullable();
        });

        Schema::table('ad_events', function (Blueprint $table) {
            //
            $table->string('key', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            //
            $table->dropColumn('key');
        });

        Schema::table('ad_events', function (Blueprint $table) {
            //
            $table->dropColumn('key');
        });
    }
}
