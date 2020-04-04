<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentCommunities extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_communities', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->text('description');
            $table->text('sections');
            $table->string('video_id');
            $table->string('video_type')->default('youtube');
            $table->string('coordinates');
            $table->string('website');
            $table->string('slug');
            $table->boolean('published')->default(0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_communities');
    }
}
