<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentBusinessEntities7 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_business_entities', function($table)
        {
            $table->string('photo')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_business_entities', function($table)
        {
            $table->dropColumn('photo');
        });
    }
}
