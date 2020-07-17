<?php

namespace cs\Backendteams\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BackendFilesAddTeam extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('system_files', 'team_id')) {
            Schema::table('system_files', function ($table) {
                $table->unsignedBigInteger('team_id')->nullable();
                $table->foreign('team_id')->references('id')->on('bl_teams_teams')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('system_files', 'team_id')) {
            Schema::table('system_files', function ($table) {
                DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                $table->dropForeign('system_files_team_id_foreign');
                $table->dropColumn('team_id');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            });
        }
    }
}
