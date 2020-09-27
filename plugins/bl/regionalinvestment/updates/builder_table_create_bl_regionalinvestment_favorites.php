<?php

namespace BL\RegionalInvestment\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentFavorites extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_regionalinvestment_favorites')) {
            Schema::create('bl_regionalinvestment_favorites', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id')->unsigned();
                $table->bigInteger('i_o_id')->unsigned();
                $table->Integer('user_id')->unsigned();
            });
            if (Schema::hasTable('users')) {
                Schema::table('bl_regionalinvestment_favorites', function ($table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                });
            }
            if (Schema::hasTable('bl_regionalinvestment_investment_opportunities')) {
                Schema::table('bl_regionalinvestment_favorites', function ($table) {
                    $table->foreign('i_o_id')->references('id')->on('bl_regionalinvestment_investment_opportunities')->onDelete('cascade')->onUpdate('cascade');
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_favorites')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_regionalinvestment_favorites');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
