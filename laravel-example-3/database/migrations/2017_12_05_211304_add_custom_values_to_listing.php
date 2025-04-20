<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomValuesToListing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('custom_label_1')->nullable();
            $table->string('custom_input_1')->nullable();
            $table->string('custom_label_2')->nullable();
            $table->string('custom_input_2')->nullable();
            $table->string('custom_label_3')->nullable();
            $table->string('custom_input_3')->nullable();
            $table->string('custom_label_4')->nullable();
            $table->string('custom_input_4')->nullable();
            $table->string('custom_label_5')->nullable();
            $table->string('custom_input_5')->nullable();
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
