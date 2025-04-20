<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZipcodeNullableUserProfessionalInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_professional_informations', function (Blueprint $table) {
            $table->dropColumn(['zip_code']);
        });

        Schema::table('user_professional_informations', function (Blueprint $table) {
            $table->string('zip_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_professional_informations', function (Blueprint $table) {
            //
        });
    }
}
