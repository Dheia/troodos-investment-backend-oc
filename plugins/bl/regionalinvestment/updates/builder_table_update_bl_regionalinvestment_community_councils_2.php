<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentCommunityCouncils2 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_community_councils', function($table)
        {
            $table->boolean('published');
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_community_councils', function($table)
        {
            $table->dropColumn('published');
        });
    }
}
