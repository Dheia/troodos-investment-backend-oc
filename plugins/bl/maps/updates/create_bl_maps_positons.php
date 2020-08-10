<?php

namespace BL\Maps\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateBlMapsPositions extends Migration
{

    public function up()
    {
        if (!Schema::hasTable('bl_maps_positions')) {
            Schema::create('bl_maps_positions', function ($table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->bigInteger('map_id')->unsigned()->nullable();
                $table->string('model_id')->index()->nullable();
                $table->string('model_type')->index()->nullable();
                $table->text('coords')->nullable();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->text('url')->nullable();
                $table->text('external_url')->nullable();

                $table->foreign('map_id')->references('id')->on('bl_maps_maps')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_maps_positions')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_maps_positions');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
