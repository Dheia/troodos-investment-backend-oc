<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteBlRegionalinvestmentBusinessEntityRegion extends Migration
{
    public function up()
    {
        Schema::dropIfExists('bl_regionalinvestment_business_entity_region');
    }
    
    public function down()
    {
        Schema::create('bl_regionalinvestment_business_entity_region', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('business_entity_id')->unsigned();
            $table->bigInteger('region_id')->unsigned();
        });
    }
}
