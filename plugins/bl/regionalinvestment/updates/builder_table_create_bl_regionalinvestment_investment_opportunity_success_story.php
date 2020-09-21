<?php

namespace BL\RegionalInvestment\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentInvestmentOpportunitySuccessStory extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_regionalinvestment_i_o_s_s')) {
            Schema::create('bl_regionalinvestment_i_o_s_s', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigInteger('i_o_id')->unsigned();
                $table->bigInteger('s_s_id')->unsigned();
            });
            if (Schema::hasTable('bl_regionalinvestment_success_stories')) {
                Schema::table('bl_regionalinvestment_i_o_s_s', function ($table) {
                    $table->foreign('s_s_id')->references('id')->on('bl_regionalinvestment_success_stories')->onDelete('cascade')->onUpdate('cascade');
                });
            }
            if (Schema::hasTable('bl_regionalinvestment_investment_opportunities')) {
                Schema::table('bl_regionalinvestment_i_o_s_s', function ($table) {
                    $table->foreign('i_o_id')->references('id')->on('bl_regionalinvestment_investment_opportunities')->onDelete('cascade')->onUpdate('cascade');
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_i_o_s_s')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_regionalinvestment_i_o_s_s');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
