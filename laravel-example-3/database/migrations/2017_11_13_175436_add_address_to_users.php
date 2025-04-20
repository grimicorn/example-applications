<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Support\HasStates;


class AddAddressToUsers extends Migration
{
    use HasStates;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_professional_informations', function (Blueprint $table) {
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->text('city')->nullable();
            $table->enum('state', array_keys($this->getStates()))->nullable();
            $table->string('zip')->nullable();
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
            //
        });
    }
}
