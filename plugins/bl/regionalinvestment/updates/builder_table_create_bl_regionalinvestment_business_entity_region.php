<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentBusinessEntityRegion extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_business_entity_region', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('business_entity_id')->unsigned();
            $table->bigInteger('region_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_business_entity_region');
    }
}
