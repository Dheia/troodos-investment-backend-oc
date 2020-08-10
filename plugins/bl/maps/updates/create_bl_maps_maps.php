<?php

namespace BL\Maps\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateBlMapsMaps extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_maps_maps')) {
            Schema::create('bl_maps_maps', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id');
                $table->string('name');
                $table->text('coords')->nullable();
                $table->string('type')->nullable();
                $table->integer('image_id')->unsigned()->nullable();
                $table->foreign('image_id')->references('id')->on('system_files')->onDelete('set null')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_maps_maps')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_maps_maps');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
