<?php

namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentInvestmentOpportunities extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('bl_regionalinvestment_investment_opportunities', 'photos')) {
            Schema::table('bl_regionalinvestment_investment_opportunities', function ($table) {
                $table->text('photos')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('bl_regionalinvestment_investment_opportunities', 'photos')) {
            Schema::table('bl_regionalinvestment_investment_opportunities', function ($table) {
                $table->dropColumn('photos');
            });
        }
    }
}
