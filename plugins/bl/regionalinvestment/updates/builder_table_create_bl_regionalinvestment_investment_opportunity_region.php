<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentInvestmentOpportunityRegion extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_investment_opportunity_region', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('investment_opportunity_id')->unsigned();
            $table->bigInteger('region_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_investment_opportunity_region');
    }
}
