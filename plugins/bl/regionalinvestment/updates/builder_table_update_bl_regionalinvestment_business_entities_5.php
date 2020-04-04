<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentBusinessEntities5 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_business_entities', function($table)
        {
            $table->renameColumn('business_status_id', 'business_entity_status_id');
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_business_entities', function($table)
        {
            $table->renameColumn('business_entity_status_id', 'business_status_id');
        });
    }
}
