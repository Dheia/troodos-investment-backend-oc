<?php

namespace cs\Backendteams\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBackendModelUsers extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('backend_users', 'team_id')) {
            Schema::table('backend_users', function ($table) {
                $table->unsignedBigInteger('team_id')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('backend_users', 'team_id')) {
            Schema::table('backend_users', function ($table) {
                $table->dropColumn('team_id');
            });
        }
    }
}
