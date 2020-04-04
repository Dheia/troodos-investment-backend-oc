<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentPointsOfInterest4 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_points_of_interest', function($table)
        {
            $table->integer('sort_order')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_points_of_interest', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
