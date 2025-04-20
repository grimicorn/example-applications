<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingExitSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_exit_surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('listing_id');
            $table->integer('final_sale_price')->nullable();
            $table->tinyInteger('overall_experience_rating')->nullable();
            $table->text('overall_experience_feedback')->nullable();
            $table->text('products_services')->nullable();
            $table->text('participant_message')->nullable();
            $table->foreign('listing_id')->references('id')->on('listings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listing_exit_surveys');
    }
}
