<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentCommunityPointOfInterest extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_community_point_of_interest', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('community_id')->unsigned();
            $table->bigInteger('point_of_interest_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_community_point_of_interest');
    }
}
