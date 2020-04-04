<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentBusinessEntities2 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_business_entities', function($table)
        {
            $table->integer('sort_order');
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_business_entities', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
