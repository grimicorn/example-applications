<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedSearchListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_search_listings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('saved_search_id');
            $table->unsignedInteger('listing_id');
            $table->timestamps();
            $table->foreign('saved_search_id')->references('id')->on('saved_searches');
            $table->foreign('listing_id')->references('id')->on('listings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saved_search_listings');
    }
}
