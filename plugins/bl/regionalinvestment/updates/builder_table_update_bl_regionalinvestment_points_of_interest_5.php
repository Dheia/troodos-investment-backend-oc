<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentPointsOfInterest5 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_points_of_interest', function($table)
        {
            $table->integer('sort_order')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_points_of_interest', function($table)
        {
            $table->integer('sort_order')->nullable(false)->change();
        });
    }
}
