<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentCommunityCouncils4 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_community_councils', function($table)
        {
            $table->string('slug');
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_community_councils', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
