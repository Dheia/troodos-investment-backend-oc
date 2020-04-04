<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentOrganizations extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_organizations', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->text('description');
            $table->string('website');
            $table->string('email');
            $table->string('phone');
            $table->text('contact_details');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_organizations');
    }
}
