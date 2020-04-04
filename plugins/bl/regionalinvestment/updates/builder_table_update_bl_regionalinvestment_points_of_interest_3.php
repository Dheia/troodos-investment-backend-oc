<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentPointsOfInterest3 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_points_of_interest', function($table)
        {
            $table->bigInteger('region_id')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_points_of_interest', function($table)
        {
            $table->dropColumn('region_id');
        });
    }
}
