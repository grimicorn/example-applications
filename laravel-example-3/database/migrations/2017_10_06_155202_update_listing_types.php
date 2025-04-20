<?php

use App\Support\HasListingTypes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateListingTypes extends Migration
{
    use HasListingTypes;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('listings', function (Blueprint $table) {
        //     $types = collect($this->getListingTypes())
        //     ->map(function ($value) {
        //         return "'{$value}'";
        //     })->implode(',');
        //     DB::statement("ALTER TABLE listings CHANGE `type` `type` ENUM({$types})");
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
