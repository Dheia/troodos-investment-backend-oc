<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentRegions11 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_regions', function($table)
        {
            $table->boolean('primary')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_regions', function($table)
        {
            $table->dropColumn('primary');
        });
    }
}
