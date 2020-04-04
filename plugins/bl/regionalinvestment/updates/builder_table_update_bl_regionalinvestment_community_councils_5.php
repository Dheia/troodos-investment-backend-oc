<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentCommunityCouncils5 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_community_councils', function($table)
        {
            $table->integer('sort_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_community_councils', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
