<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlRegionalinvestmentOrganizationRegion extends Migration
{
    public function up()
    {
        Schema::create('bl_regionalinvestment_organization_region', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('region_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bl_regionalinvestment_organization_region');
    }
}
