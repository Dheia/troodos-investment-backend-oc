<?php

namespace BL\Maps\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateBlMapsMapsSortOrder extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('bl_maps_maps', 'sort_order')) {
            Schema::table('bl_maps_maps', function ($table) {
                $table->integer('sort_order')->nullable()->default(0);
            });
        }
    }

    public function down()
    {
        Schema::table('bl_maps_maps', function ($table) {
            if (Schema::hasColumn('bl_maps_maps', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
}
