<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentBusinessEntitiesBusinessEntityStatus extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_business_entities_business_entity_status', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('business_entity_id')->unsigned();
            $table->bigInteger('business_entity_status_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_business_entities_business_entity_status');
    }
}
