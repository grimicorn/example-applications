<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAmountFromRevenues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('revenues', function (Blueprint $table) {
            if (Schema::hasColumn('revenues', 'amount')) {
                $table->dropColumn('amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('revenues', function (Blueprint $table) {
            //
        });
    }
}
