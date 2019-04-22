<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingPointGive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('setting_point_give');
        Schema::create('setting_point_give', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enable')->default(true);
            $table->text('json')->nullable();
            $table->timestamps();
        });

        DB::table('setting_point_give')->insert([
            ['json' => '{"register":3000}'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('setting_point_give');
    }
}
