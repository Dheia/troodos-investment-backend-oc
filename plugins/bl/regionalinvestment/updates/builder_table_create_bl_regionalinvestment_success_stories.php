<?php

namespace BL\RegionalInvestment\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentSuccessStories extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_regionalinvestment_success_stories')) {
            Schema::create('bl_regionalinvestment_success_stories', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id')->unsigned();
                $table->bigInteger('region_id')->nullable()->unsigned();
                $table->string('slug');
                $table->string('name');
                $table->text('description');
                $table->string('photo')->nullable();
                $table->string('video_id')->nullable();
                $table->string('video_type')->nullable()->default('youtube');
                $table->boolean('published')->nullable();
                $table->string('website')->nullable();
                $table->integer('sort_order')->nullable()->unsigned();
            });
            if (Schema::hasTable('bl_regionalinvestment_regions')) {
                Schema::table('bl_regionalinvestment_success_stories', function ($table) {
                    $table->foreign('region_id')->references('id')->on('bl_regionalinvestment_regions')->onDelete('set null')->onUpdate('cascade');
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_success_stories')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_regionalinvestment_success_stories');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
