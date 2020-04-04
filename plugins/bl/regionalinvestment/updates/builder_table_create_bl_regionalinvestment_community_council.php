<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentCommunityCouncil extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_community_council', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('website');
            $table->string('email');
            $table->string('phone');
            $table->text('contact_details');
            $table->text('description');
            $table->bigInteger('community_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_community_council');
    }
}
