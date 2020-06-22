<?php

namespace BL\RegionalInvestment\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentBusinessTypes extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_regionalinvestment_business_types')) {
            Schema::create('bl_regionalinvestment_business_types', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id')->unsigned();
                $table->string('slug');
                $table->string('name');
                $table->integer('sort_order')->nullable()->unsigned();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_business_types')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_regionalinvestment_business_types');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
