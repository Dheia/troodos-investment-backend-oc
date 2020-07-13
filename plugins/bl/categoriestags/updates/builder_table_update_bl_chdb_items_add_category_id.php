<?php

namespace BL\CHDB\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBlChdbItemsAddCategoryId extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bl_chdb_items') && !Schema::hasColumn('bl_chdb_items', 'category_id')) {
            Schema::table('bl_chdb_items', function ($table) {
                $table->bigInteger('category_id')->nullable()->unsigned();
                $table->foreign('category_id')->references('id')->on('bl_categoriestags_categories')->onDelete('set null')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_chdb_items') && Schema::hasColumn('bl_chdb_items', 'category_id')) {
            Schema::table('bl_chdb_items', function ($table) {
                $table->dropColumn('category_id');
            });
        }
    }
}
