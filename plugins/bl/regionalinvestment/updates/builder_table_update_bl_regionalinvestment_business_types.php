<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentBusinessTypes extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_business_types', function($table)
        {
            $table->string('slug');
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_business_types', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
