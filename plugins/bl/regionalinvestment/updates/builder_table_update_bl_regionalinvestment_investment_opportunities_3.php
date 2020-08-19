<?php

namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentInvestmentOpportunities3 extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('bl_regionalinvestment_investment_opportunities', 'map_id')) {
            Schema::table('bl_regionalinvestment_investment_opportunities', function ($table) {
                $table->dropColumn('map_id');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('bl_regionalinvestment_investment_opportunities', 'map_id')) {
            Schema::table('bl_regionalinvestment_investment_opportunities', function ($table) {
                $table->bigInteger('map_id')->nullable()->unsigned();
            });
        }
    }
}
