<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentCommunityCouncils3 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_community_councils', function($table)
        {
            $table->string('website', 191)->nullable()->change();
            $table->text('contact_details')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->boolean('published')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_community_councils', function($table)
        {
            $table->string('website', 191)->nullable(false)->change();
            $table->text('contact_details')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->boolean('published')->nullable(false)->change();
        });
    }
}
