<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentInvestmentOpportunities4 extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_investment_opportunities', function($table)
        {
            $table->decimal('investment_target', 10, 2)->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_investment_opportunities', function($table)
        {
            $table->string('investment_target', 191)->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
