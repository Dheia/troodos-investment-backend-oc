<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentCommunityCouncils extends Migration
{
    public function up()
    {
        Schema::rename('bl_regionalinvestment_community_council', 'bl_regionalinvestment_community_councils');
    }
    
    public function down()
    {
        Schema::rename('bl_regionalinvestment_community_councils', 'bl_regionalinvestment_community_council');
    }
}
