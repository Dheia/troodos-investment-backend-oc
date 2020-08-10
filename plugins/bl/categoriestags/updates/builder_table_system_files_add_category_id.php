<?php

namespace cs\Backendteams\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableSystemFilesAddCategoryId extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('system_files', 'category_id')) {
            Schema::table('system_files', function ($table) {
                $table->unsignedBigInteger('category_id')->nullable();
                $table->foreign('category_id')->references('id')->on('bl_categoriestags_categories')->onDelete('set null')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('system_files', 'category_id')) {
            Schema::table('system_files', function ($table) {
                DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                $table->dropColumn('category_id');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            });
        }
    }
}
