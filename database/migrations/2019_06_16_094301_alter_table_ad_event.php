<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAdEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_events', function (Blueprint $table) {
            //
            $table->boolean('show_index')->default(false);
            $table->integer('link_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_events', function (Blueprint $table) {
            //
            $table->dropColumn('show_index');
            $table->dropColumn('link_type');
        });
    }
}
