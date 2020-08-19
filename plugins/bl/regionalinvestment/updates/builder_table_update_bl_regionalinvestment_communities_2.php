<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentCommunities2 extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bl_regionalinvestment_communities') && !Schema::hasColumn('bl_regionalinvestment_communities', 'photos')) {
            Schema::table('bl_regionalinvestment_communities', function($table)
            {
                $table->text('photos')->nullable();
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_communities') && Schema::hasColumn('bl_regionalinvestment_communities', 'photos')) {
            Schema::table('bl_regionalinvestment_communities', function($table)
            {
                $table->dropColumn('photos');
            });
        }
    }
}
