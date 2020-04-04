<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentCommunityInvestmentOpportunity extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_community_investment_opportunity', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('community_id')->unsigned();
            $table->bigInteger('investment_opportunity_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_community_investment_opportunity');
    }
}
