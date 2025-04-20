<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Support\ExchangeSpace\MemberRole;
use Illuminate\Database\Migrations\Migration;

class AddAdministratorRoleToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
}
