<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentOrganizations2 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_organizations', function($table)
        {
            $table->text('contact_details')->nullable()->change();
            $table->boolean('published')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_organizations', function($table)
        {
            $table->text('contact_details')->nullable(false)->change();
            $table->boolean('published')->nullable(false)->change();
        });
    }
}
