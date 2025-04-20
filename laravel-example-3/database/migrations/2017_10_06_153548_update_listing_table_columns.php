<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateListingTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->text('real_estate_description')->nullable();
            $table->text('fixtures_equipment_description')->nullable();
            $table->text('inventory_description')->nullable();
            $table->text('links')->nullable(); // Add to casts array
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
            $table->dropColumn('real_estate_description');
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('fixtures_equipment_description');
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('inventory_description');
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('links');
        });
    }
}
