<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestment extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_', function($table)
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
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_');
    }
}
