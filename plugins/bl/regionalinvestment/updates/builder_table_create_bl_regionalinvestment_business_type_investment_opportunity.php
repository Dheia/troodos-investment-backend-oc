<?php

namespace BL\RegionalInvestment\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentBusinessTypeInvestmentOpportunity extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_regionalinvestment_business_type_i_o')) {
            Schema::create('bl_regionalinvestment_business_type_i_o', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigInteger('b_t_id')->unsigned();
                $table->bigInteger('i_o_id')->unsigned();
            });
        }
        if (Schema::hasTable('bl_regionalinvestment_business_types')) {
            Schema::table('bl_regionalinvestment_business_type_i_o', function ($table) {
                $table->foreign('b_t_id')->references('id')->on('bl_regionalinvestment_business_types')->onDelete('cascade')->onUpdate('cascade');
            });
        }
        if (Schema::hasTable('bl_regionalinvestment_investment_opportunities')) {
            Schema::table('bl_regionalinvestment_business_type_i_o', function ($table) {
                $table->foreign('i_o_id')->references('id')->on('bl_regionalinvestment_investment_opportunities')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_business_type_i_o')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_regionalinvestment_business_type_i_o');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
