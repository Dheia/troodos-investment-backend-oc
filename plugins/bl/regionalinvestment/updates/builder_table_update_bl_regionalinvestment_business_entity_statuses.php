<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentBusinessEntityStatuses extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_business_entity_statuses', function($table)
        {
            $table->string('slug');
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_business_entity_statuses', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
