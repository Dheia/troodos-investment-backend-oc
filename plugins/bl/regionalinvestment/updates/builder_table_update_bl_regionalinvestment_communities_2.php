<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentCommunities2 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_communities', function($table)
        {
            $table->text('photos')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_communities', function($table)
        {
            $table->dropColumn('photos');
        });
    }
}
