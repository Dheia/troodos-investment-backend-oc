<?php

namespace BL\CategoriesTags\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlCategoriesTagsTags extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_categoriestags_tags')) {
            Schema::create('bl_categoriestags_tags', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id');
                $table->text('name');
                $table->text('slug');
                $table->integer('sort_order')->default(0);
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_categoriestags_tags')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_categoriestags_tags');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
