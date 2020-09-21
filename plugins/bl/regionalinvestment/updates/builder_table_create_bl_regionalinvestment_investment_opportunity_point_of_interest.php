<?php

namespace BL\RegionalInvestment\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentInvestmentOpportunityPointOfInterest extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_regionalinvestment_i_o_p_o_i')) {
            Schema::create('bl_regionalinvestment_i_o_p_o_i', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigInteger('i_o_id')->unsigned();
                $table->bigInteger('p_o_i_id')->unsigned();
            });
            if (Schema::hasTable('bl_regionalinvestment_points_of_interest')) {
                Schema::table('bl_regionalinvestment_i_o_p_o_i', function ($table) {
                    $table->foreign('p_o_i_id')->references('id')->on('bl_regionalinvestment_points_of_interest')->onDelete('cascade')->onUpdate('cascade');
                });
            }
            if (Schema::hasTable('bl_regionalinvestment_investment_opportunities')) {
                Schema::table('bl_regionalinvestment_i_o_p_o_i', function ($table) {
                    $table->foreign('i_o_id')->references('id')->on('bl_regionalinvestment_investment_opportunities')->onDelete('cascade')->onUpdate('cascade');
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_i_o_p_o_i')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_regionalinvestment_i_o_p_o_i');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
