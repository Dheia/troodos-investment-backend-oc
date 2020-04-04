<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentBusinessTypeInvestmentOpportunity extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_business_type_investment_opportunity', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('business_type_id')->unsigned();
            $table->bigInteger('investment_opportunity_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_business_type_investment_opportunity');
    }
}
