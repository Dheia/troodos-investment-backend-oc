<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentPointsOfInterest extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('bl_regionalinvestment_points_of_interest', 'map_id')) {
            Schema::table('bl_regionalinvestment_points_of_interest', function($table)
            {
                $table->bigInteger('map_id')->nullable()->unsigned();
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('bl_regionalinvestment_points_of_interest', 'map_id')) {
            Schema::table('bl_regionalinvestment_points_of_interest', function($table)
            {
                $table->dropColumn('map_id');
            });
        }
    }
}
