<?php

use App\Support\HasOccupations;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveOccupationOtherFromUserProfessionalInformations extends Migration
{
    use HasOccupations;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_professional_informations', function (Blueprint $table) {
            if (Schema::hasColumn('user_professional_informations', 'occupation_other')) {
                $table->dropColumn('occupation_other');
            }
        });

        Schema::table('user_professional_informations', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            // $statuses = collect($this->getUserOccupations())->map(function ($value) {
            //     return "'{$value}'";
            // })->implode(',');
            // DB::statement("ALTER TABLE user_professional_informations CHANGE occupation occupation ENUM({$statuses})");
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
