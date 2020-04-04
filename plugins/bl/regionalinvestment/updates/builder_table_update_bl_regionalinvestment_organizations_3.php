<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentOrganizations3 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_organizations', function($table)
        {
            $table->string('slug');
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_organizations', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
