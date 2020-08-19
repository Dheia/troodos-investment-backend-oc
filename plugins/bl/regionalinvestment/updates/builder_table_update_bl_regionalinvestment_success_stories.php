<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentSuccessStories extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('bl_regionalinvestment_success_stories', 'map_id')) {
            Schema::table('bl_regionalinvestment_success_stories', function($table)
            {
                $table->bigInteger('map_id')->nullable()->unsigned();
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('bl_regionalinvestment_success_stories', 'map_id')) {
            Schema::table('bl_regionalinvestment_success_stories', function($table)
            {
                $table->dropColumn('map_id');
            });
        }
    }
}
