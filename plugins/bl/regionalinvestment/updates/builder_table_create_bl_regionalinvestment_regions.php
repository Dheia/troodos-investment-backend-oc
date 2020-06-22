<?php

namespace BL\RegionalInvestment\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestment extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_regionalinvestment_regions')) {
            Schema::create('bl_regionalinvestment_regions', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id');
                $table->boolean('published')->nullable()->default(false);
                $table->boolean('primary')->nullable();
                $table->string('slug');
                $table->string('name');
                $table->text('description')->nullable();
                $table->text('sections')->nullable();
                $table->string('photo')->nullable();
                $table->text('photos')->nullable();
                $table->string('video_id')->nullable();
                $table->string('video_type')->nullable()->default('youtube');
                $table->string('website')->nullable();
                $table->integer('sort_order')->nullable()->unsigned();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_regions')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_regionalinvestment_regions');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
