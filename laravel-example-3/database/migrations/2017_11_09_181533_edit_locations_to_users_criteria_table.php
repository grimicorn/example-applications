<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditLocationsToUsersCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_desired_purchase_criterias', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            DB::statement('ALTER TABLE user_desired_purchase_criterias MODIFY locations TEXT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_desired_purchase_criterias', function (Blueprint $table) {
            //
        });
    }
}
