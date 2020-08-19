<?php

namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentInvestmentOpportunities2 extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('bl_regionalinvestment_investment_opportunities', 'photos')) {
            Schema::table('bl_regionalinvestment_investment_opportunities', function ($table) {
                $table->text('photos')->nullable();
            });
        }
        if (!Schema::hasColumn('bl_regionalinvestment_investment_opportunities', 'map_id')) {
            Schema::table('bl_regionalinvestment_investment_opportunities', function ($table) {
                $table->bigInteger('map_id')->nullable()->unsigned();
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
        if (Schema::hasColumn('bl_regionalinvestment_investment_opportunities', 'map_id')) {
            Schema::table('bl_regionalinvestment_investment_opportunities', function ($table) {
                $table->dropColumn('map_id');
            });
        }
    }
}
