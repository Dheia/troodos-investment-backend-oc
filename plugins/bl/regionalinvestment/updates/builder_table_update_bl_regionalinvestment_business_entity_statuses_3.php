<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentBusinessEntityStatuses3 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_business_entity_statuses', function($table)
        {
            $table->integer('sort_order')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_business_entity_statuses', function($table)
        {
            $table->integer('sort_order')->nullable(false)->change();
        });
    }
}
