<?php

namespace BL\Teams\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBlTeamsTeams extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bl_teams_teams')) {
            Schema::create('bl_teams_teams', function ($table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->text('address')->nullable();
                $table->string('email')->nullable();
                $table->unsignedInteger('user_id');
                $table->integer('sort_order')->nullable()->default(0);
                $table->string('requested_domain', 191)->nullable();
                $table->string('domain', 191)->nullable();
                $table->boolean('custom_domain_only')->nullable();

                $table->foreign('user_id')->references('id')->on('backend_users')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bl_teams_teams')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('bl_teams_teams');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
