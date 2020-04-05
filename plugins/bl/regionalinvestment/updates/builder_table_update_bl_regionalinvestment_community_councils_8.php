<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentCommunityCouncils8 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_community_councils', function($table)
        {
            $table->string('photo', 191)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_community_councils', function($table)
        {
            $table->string('photo', 191)->nullable(false)->change();
        });
    }
}
