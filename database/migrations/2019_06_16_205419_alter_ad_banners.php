<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_banners', function (Blueprint $table) {
            //
            $table->string('link_type')->nullable();
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
        Schema::table('ad_banners', function (Blueprint $table) {
            //
            $table->dropColumn('link_type');
            $table->dropColumn('link_value');
        });
    }
}
