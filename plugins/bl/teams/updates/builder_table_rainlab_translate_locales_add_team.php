<?php

namespace cs\Backendteams\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableRainlabTranslateLocalesAddTeam extends Migration
{
    public function up()
    {
        if (Schema::hasTable('rainlab_translate_locales') && !Schema::hasColumn('rainlab_translate_locales', 'team_id')) {
            Schema::table('rainlab_translate_locales', function ($table) {
                $table->unsignedBigInteger('team_id')->nullable();
                $table->foreign('team_id')->references('id')->on('bl_teams_teams')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('rainlab_translate_locales') && Schema::hasColumn('rainlab_translate_locales', 'team_id')) {
            Schema::table('rainlab_translate_locales', function ($table) {
                DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                $table->dropForeign('rainlab_translate_locales_team_id_foreign');
                $table->dropColumn('team_id');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            });
        }
    }
}
