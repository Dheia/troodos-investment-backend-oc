<?php

namespace cs\Backendteams\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableRainlabTranslateAttributesAddTeam extends Migration
{
    public function up()
    {
        if (Schema::hasTable('rainlab_translate_attributes') && !Schema::hasColumn('rainlab_translate_attributes', 'team_id')) {
            Schema::table('rainlab_translate_attributes', function ($table) {
                $table->unsignedBigInteger('team_id')->nullable();
                $table->foreign('team_id')->references('id')->on('bl_teams_teams')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('rainlab_translate_attributes') && Schema::hasColumn('rainlab_translate_attributes', 'team_id')) {
            Schema::table('rainlab_translate_attributes', function ($table) {
                DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                $table->dropForeign('rainlab_translate_attributes_team_id_foreign');
                $table->dropColumn('team_id');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            });
        }
    }
}
