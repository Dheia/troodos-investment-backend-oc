<?php namespace BL\Maps\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlMapsMaps extends Migration
{
    public function up()
    {
        Schema::table('bl_maps_maps', function($table)
        {
            $table->string('slug')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('bl_maps_maps', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
