<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentBusinessEntities extends Migration
{
    public function up()
    {
        Schema::table('bl_regionalinvestment_business_entities', function($table)
        {
            $table->bigInteger('status_id')->nullable()->change();
            $table->bigInteger('business_type_id')->nullable()->change();
            $table->string('website', 191)->nullable()->change();
            $table->string('coordinates', 191)->nullable()->change();
            $table->string('email', 191)->nullable()->change();
            $table->string('phone', 191)->nullable()->change();
            $table->text('contact_details')->nullable()->change();
            $table->boolean('published')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('bl_regionalinvestment_business_entities', function($table)
        {
            $table->bigInteger('status_id')->nullable(false)->change();
            $table->bigInteger('business_type_id')->nullable(false)->change();
            $table->string('website', 191)->nullable(false)->change();
            $table->string('coordinates', 191)->nullable(false)->change();
            $table->string('email', 191)->nullable(false)->change();
            $table->string('phone', 191)->nullable(false)->change();
            $table->text('contact_details')->nullable(false)->change();
            $table->boolean('published')->nullable(false)->change();
        });
    }
}
