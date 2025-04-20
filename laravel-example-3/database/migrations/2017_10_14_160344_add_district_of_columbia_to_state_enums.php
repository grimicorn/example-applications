<?php

use App\Support\HasStates;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDistrictOfColumbiaToStateEnums extends Migration
{
    use HasStates;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            $states = collect(array_keys($this->getStates()))->map(function ($value) {
                return "'{$value}'";
            })->implode(',');
            DB::statement("ALTER TABLE listings CHANGE state state ENUM({$states})");
        });

        Schema::table('saved_searches', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            $states = collect(array_keys($this->getStates()))->map(function ($value) {
                return "'{$value}'";
            })->implode(',');
            DB::statement("ALTER TABLE saved_searches CHANGE state state ENUM({$states})");
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
