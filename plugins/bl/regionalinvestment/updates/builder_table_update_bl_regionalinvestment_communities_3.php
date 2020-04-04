<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentCommunities3 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_communities', function($table)
        {
            $table->integer('sort_order')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_communities', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
