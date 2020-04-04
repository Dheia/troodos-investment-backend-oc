<?php namespace BL\RegionalInvestment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlRegionalinvestmentRegions extends Migration
{
    public function up()
    {
        Schema::rename('bl_regionalinvestment_', 'bl_regionalinvestment_regions');
    }
    
    public function down()
    {
        Schema::rename('bl_regionalinvestment_regions', 'bl_regionalinvestment_');
    }
}
