<?php

namespace BL\CategoriesTags\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlCategoriesTagsTagables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_categoriestags_tagables')) {
            Schema::create('bl_categoriestags_tagables', function ($table) {
                $table->engine = 'InnoDB';
                $table->unsignedBigInteger('item_id');
                $table->unsignedBigInteger('tag_id');
                $table->string('tagable_type');

                $table->foreign('tag_id')->references('id')->on('bl_categoriestags_tags')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_categoriestags_tagables')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_categoriestags_tagables');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
