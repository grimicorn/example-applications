<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertBusinessCategoriesToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SQLite will choke on this.
        if (app()->environment('testing')) {
            return;
        }

        Schema::table('', function (Blueprint $table) {
            DB::statement("ALTER TABLE user_desired_purchase_criterias MODIFY business_categories TEXT");
        });

        Schema::table('saved_searches', function (Blueprint $table) {
            DB::statement("ALTER TABLE saved_searches MODIFY business_categories TEXT");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
