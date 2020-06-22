<?php

namespace BL\RegionalInvestment\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentCommunities extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_regionalinvestment_communities')) {
            Schema::create('bl_regionalinvestment_communities', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id')->unsigned();
                $table->bigInteger('region_id')->nullable()->unsigned();
                $table->boolean('published')->nullable()->default(0);
                $table->string('slug')->nullable();
                $table->string('name');
                $table->text('description');
                $table->text('sections')->nullable();
                $table->string('photo', 191)->nullable();
                $table->string('video_id')->nullable();
                $table->string('video_type')->nullable()->default('youtube');
                $table->string('website')->nullable();
                $table->integer('sort_order')->nullable()->unsigned();
            });
        }
        if (Schema::hasTable('bl_regionalinvestment_regions')) {
            Schema::table('bl_regionalinvestment_communities', function ($table) {
                $table->foreign('region_id')->references('id')->on('bl_regionalinvestment_regions')->onDelete('set null')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_communities')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_regionalinvestment_communities');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
