<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentInvestmentOpportunities extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_investment_opportunities', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->boolean('published')->nullable();
            $table->string('short_description');
            $table->text('description');
            $table->string('video_id')->nullable();
            $table->string('video_type')->nullable()->default('youtube');
            $table->string('coordinates')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_details')->nullable();
            $table->date('date_available')->nullable();
            $table->string('investment_min');
            $table->string('investment_max');
            $table->string('investment_target')->nullable();
            $table->boolean('show_target')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_investment_opportunities');
    }
}
