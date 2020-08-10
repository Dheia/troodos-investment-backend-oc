<?php

namespace cs\Backendteams\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableSystemFilesAddFullPath extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('system_files', 'full_path')) {
            Schema::table('system_files', function ($table) {
                $table->string('full_path', 256)->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('system_files', 'full_path')) {
            Schema::table('system_files', function ($table) {
                $table->dropColumn('full_path');
            });
        }
    }
}
