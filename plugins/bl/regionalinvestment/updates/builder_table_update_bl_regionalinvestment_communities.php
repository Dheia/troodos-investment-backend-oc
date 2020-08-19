<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentCommunities extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_communities', function($table)
        {
            $table->bigInteger('map_id')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_communities', function($table)
        {
            $table->dropColumn('map_id');
        });
    }
}
