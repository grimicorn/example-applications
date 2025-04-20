<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOverlayToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_overlay_tours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->boolean('dashboard_viewed')->default(false);
            $table->boolean('listing_create_viewed')->default(false);
            $table->boolean('listing_edit_viewed')->default(false);
            $table->boolean('lcs_index_viewed')->default(false);
            $table->boolean('listing_historical_financials_edit_viewed')->default(false);
            $table->boolean('exchange_space_index_viewed')->default(false);
            $table->boolean('exchange_space_show_viewed')->default(false);
            $table->boolean('diligence_center_index_viewed')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_overlay_tours');
    }
}
