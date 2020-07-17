<?php namespace BL\Maps\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateBlMapsPositionsRemoveUrl extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('bl_maps_positions', 'url')) {
            Schema::table('bl_maps_positions', function($table)
            {
                $table->dropColumn('url');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('bl_maps_positions', 'url')) {
            Schema::table('bl_maps_positions', function($table)
            {
                $table->text('url')->nullable();
            });
        }
    }
}
