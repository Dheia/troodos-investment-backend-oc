<?php namespace BL\Maps\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateBlMapsMapsZoom extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('bl_maps_maps', 'zoom')) {
            Schema::table('bl_maps_maps', function($table)
            {
                $table->integer('zoom')->nullable()->default(2);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('bl_maps_maps', 'zoom')) {
            Schema::table('bl_maps_maps', function($table)
            {
                $table->dropColumn('zoom');
            });
        }
    }
}
