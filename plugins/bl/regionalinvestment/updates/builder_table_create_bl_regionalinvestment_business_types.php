<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentBusinessTypes extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_business_types', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_business_types');
    }
}
