<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditListingAssetsIncludedType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['real_estate_included', 'fixtures_equipment_included', 'inventory_included']);
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->smallInteger('real_estate_included')->nullable();
            $table->smallInteger('fixtures_equipment_included')->nullable();
            $table->smallInteger('inventory_included')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            //
        });
    }
}
