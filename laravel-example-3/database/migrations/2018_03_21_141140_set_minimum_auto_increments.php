<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetMinimumAutoIncrements extends Migration
{
    protected $table_increment_starts = [
        'users' => 217914295,
        'listings' => 101531908,
        'exchange_spaces' => 242667040,
        'saved_searches' => 361938690,
        'conversations' => 293550233,
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->environment('testing')) {
            return;
        }

        collect($this->table_increment_starts)
        ->each(function ($increment_start, $table) {
            DB::unprepared("ALTER TABLE {$table} AUTO_INCREMENT = {$increment_start};");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (app()->environment('testing')) {
            return;
        }

        collect($this->table_increment_starts)
        ->each(function ($increment_start, $table) {
            $increment_start = DB::table('users')->max('id') + 1;
            DB::unprepared("ALTER TABLE {$table} AUTO_INCREMENT = {$increment_start};");
        });
    }
}
