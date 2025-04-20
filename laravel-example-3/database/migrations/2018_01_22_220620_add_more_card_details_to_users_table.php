<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreCardDetailsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('card_expiration_month', [
                '01',
                '02',
                '03',
                '04',
                '05',
                '06',
                '07',
                '08',
                '09',
                '10',
                '11',
                '12',
            ])->nullable();
            $table->integer('card_expiration_year')->nullable();
            $table->text('card_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('card_expiration_month');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('card_expiration_year');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('card_name');
        });
    }
}
