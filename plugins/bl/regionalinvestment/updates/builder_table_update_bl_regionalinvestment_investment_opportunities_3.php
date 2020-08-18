<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentInvestmentOpportunities3 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_investment_opportunities', function($table)
        {
            $table->dropColumn('map_id');
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_investment_opportunities', function($table)
        {
            $table->bigInteger('map_id')->nullable()->unsigned();
        });
    }
}
