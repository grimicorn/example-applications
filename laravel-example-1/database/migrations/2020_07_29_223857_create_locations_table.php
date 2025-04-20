<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('name');
            $table->decimal('latitude', 9, 7);
            $table->decimal('longitude', 9, 6);
            $table->string('timezone');
            $table->string('route')->nullable();
            $table->string('locality')->nullable();
            $table->string('administrative_area_level_1_abbreviation')->nullable();
            $table->string('administrative_area_level_1')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('google_maps_link');
            $table->text('best_time_of_day_to_visit')->nullable();
            $table->text('best_time_of_year_to_visit')->nullable();
            $table->float('rating')->nullable();
            $table->boolean('visited')->default(false);
            $table->text('notes')->nullable();
            $table->unsignedInteger('icon_id')->nullable();
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
        Schema::dropIfExists('locations');
    }
}
