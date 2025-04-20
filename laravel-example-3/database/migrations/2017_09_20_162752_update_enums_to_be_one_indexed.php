<?php

use Illuminate\Support\Facades\DB;
use App\Support\ExchangeSpaceDealType;
use Illuminate\Support\Facades\Schema;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ExchangeSpace\MemberRole;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEnumsToBeOneIndexed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_spaces', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            $statuses = collect(ExchangeSpaceStatusType::getValues())->map(function ($value) {
                return "'{$value}'";
            })->implode(',');
            DB::statement("ALTER TABLE exchange_spaces CHANGE status status ENUM({$statuses})");

            $deals = collect(ExchangeSpaceDealType::getValues())->map(function ($value) {
                return "'{$value}'";
            })->implode(',');
            DB::statement("ALTER TABLE exchange_spaces CHANGE deal deal ENUM({$deals})");
        });

        Schema::table('members', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            $roles = collect(MemberRole::getValues())->map(function ($value) {
                return "'{$value}'";
            })->implode(',');
            DB::statement("ALTER TABLE members CHANGE role role ENUM({$roles})");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_spaces', function (Blueprint $table) {
            //
        });
    }
}
