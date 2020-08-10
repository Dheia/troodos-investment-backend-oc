<?php

namespace BL\MediaLibrary\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlMedialibraryMediaLibrary extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_medialibrary_media_library')) {
            Schema::create('bl_medialibrary_media_library', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->string('title', 256)->nullable();
                $table->string('description', 512)->nullable();
                $table->integer('sort_order')->nullable();
                $table->bigInteger('team_id')->unsigned()->nullable();
            });
            if (Schema::hasTable('bl_teams_teams')) {
                Schema::table('bl_medialibrary_media_library', function ($table) {
                    $table->foreign('team_id')->references('id')->on('bl_teams_teams')->onDelete('cascade')->onUpdate('cascade');
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_medialibrary_media_library')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_medialibrary_media_library');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
