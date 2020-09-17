<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentInvestmentOpportunities5 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_investment_opportunities', function($table)
        {
            $table->text('contact_details')->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_investment_opportunities', function($table)
        {
            $table->string('contact_details', 191)->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
