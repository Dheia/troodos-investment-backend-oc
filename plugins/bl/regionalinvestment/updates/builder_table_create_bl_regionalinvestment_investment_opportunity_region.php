<?php

namespace BL\RegionalInvestment\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentInvestmentOpportunityRegion extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_regionalinvestment_i_o_region')) {
            Schema::create('bl_regionalinvestment_i_o_region', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigInteger('i_o_id')->unsigned();
                $table->bigInteger('r_id')->unsigned();
            });
            if (Schema::hasTable('bl_regionalinvestment_regions')) {
                Schema::table('bl_regionalinvestment_i_o_region', function ($table) {
                    $table->foreign('r_id')->references('id')->on('bl_regionalinvestment_regions')->onDelete('cascade')->onUpdate('cascade');
                });
            }
            if (Schema::hasTable('bl_regionalinvestment_investment_opportunities')) {
                Schema::table('bl_regionalinvestment_i_o_region', function ($table) {
                    $table->foreign('i_o_id')->references('id')->on('bl_regionalinvestment_investment_opportunities')->onDelete('cascade')->onUpdate('cascade');
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_i_o_region')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_regionalinvestment_i_o_region');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
