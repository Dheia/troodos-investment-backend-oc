<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentRegions5 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_regions', function($table)
        {
            $table->text('sections')->nullable()->change();
            $table->string('video_id', 191)->nullable()->change();
            $table->string('video_type', 191)->nullable()->change();
            $table->string('coordinates', 191)->nullable()->change();
            $table->string('website', 191)->nullable()->change();
            $table->boolean('published')->nullable()->change();
            $table->bigInteger('region_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_regions', function($table)
        {
            $table->text('sections')->nullable(false)->change();
            $table->string('video_id', 191)->nullable(false)->change();
            $table->string('video_type', 191)->nullable(false)->change();
            $table->string('coordinates', 191)->nullable(false)->change();
            $table->string('website', 191)->nullable(false)->change();
            $table->boolean('published')->nullable(false)->change();
            $table->bigInteger('region_id')->nullable(false)->change();
        });
    }
}
