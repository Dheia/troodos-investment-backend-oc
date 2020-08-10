<?php

namespace BL\Maps\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateBlMapsMapsCoords extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('bl_maps_maps', 'coords')) {
            Schema::table('bl_maps_maps', function ($table) {
                $table->dropColumn('coords');
                $table->string('coord_x')->nullable();
                $table->string('coord_y')->nullable();
            });
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('bl_maps_maps', 'coords')) {
            Schema::table('bl_maps_maps', function ($table) {
                $table->text('coords')->nullable();
                $table->dropColumn('coord_x');
                $table->dropColumn('coord_y');
            });
        }
    }
}
