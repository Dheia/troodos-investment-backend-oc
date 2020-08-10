<?php

namespace cs\Backendteams\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatecsBackendteamsTeammembers extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_teams_team_user')) {
            Schema::create('bl_teams_team_user', function ($table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->unsignedBigInteger('team_id');
                $table->string('role')->nullable();
                $table->integer('sort_order')->nullable()->default(0);

                $table->foreign('user_id')->references('id')->on('backend_users')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('team_id')->references('id')->on('bl_teams_teams')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_teams_team_user')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_teams_team_user');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
