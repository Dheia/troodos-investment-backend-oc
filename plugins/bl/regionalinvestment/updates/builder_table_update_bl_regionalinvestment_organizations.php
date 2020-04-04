<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentOrganizations extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_organizations', function($table)
        {
            $table->boolean('published');
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_organizations', function($table)
        {
            $table->dropColumn('published');
        });
    }
}
