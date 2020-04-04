<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentBusinessEntities extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_business_entities', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->bigInteger('status_id');
            $table->bigInteger('business_type_id');
            $table->string('website');
            $table->string('coordinates');
            $table->string('email');
            $table->string('phone');
            $table->text('contact_details');
            $table->text('description');
            $table->boolean('published');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_business_entities');
    }
}
