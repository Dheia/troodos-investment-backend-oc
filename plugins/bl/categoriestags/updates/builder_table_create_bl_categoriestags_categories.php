<?php

namespace BL\CategoriesTags\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlCategoriesTagsCategories extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_categoriestags_categories')) {
            Schema::create('bl_categoriestags_categories', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id');
                $table->unsignedBigInteger('team_id')->nullable();
                $table->text('code');
                $table->text('name');
                $table->integer('sort_order')->default(0);
            });
            if (Schema::hasTable('bl_teams_teams')) {
                Schema::table('bl_categoriestags_categories', function ($table) {
                    $table->foreign('team_id')->references('id')->on('bl_teams_teams')->onDelete('cascade')->onUpdate('cascade');
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_categoriestags_categories')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_categoriestags_categories');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
