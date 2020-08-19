<?php

namespace BL\RegionalInvestment\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentInvestmentOpportunities extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_regionalinvestment_investment_opportunities')) {
            Schema::create('bl_regionalinvestment_investment_opportunities', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id')->unsigned();
                $table->string('name');
                $table->string('slug');
                $table->boolean('published')->nullable();
                $table->string('short_description')->nullable();
                $table->text('description');
                $table->text('sections')->nullable();
                $table->string('photo', 191)->nullable();
                $table->string('video_id')->nullable();
                $table->string('video_type')->nullable()->default('youtube');
                $table->string('coordinates')->nullable();
                $table->string('website')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('contact_details')->nullable();
                $table->date('date_available')->nullable();
                $table->string('investment_target')->nullable();
                $table->boolean('show_target')->nullable();
                $table->integer('sort_order')->nullable()->unsigned();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_regionalinvestment_investment_opportunities')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_regionalinvestment_investment_opportunities');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
