<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentPointsOfInterest extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_points_of_interest', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->text('description');
            $table->string('video_id');
            $table->string('video_type')->default('youtube');
            $table->string('coordinates');
            $table->boolean('published');
            $table->string('website');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_points_of_interest');
    }
}
