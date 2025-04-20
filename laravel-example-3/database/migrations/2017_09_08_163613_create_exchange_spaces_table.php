<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ExchangeSpaceDealType;

class CreateExchangeSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_spaces', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->enum('status', ExchangeSpaceStatusType::getValues());
            $table->enum('deal', ExchangeSpaceDealType::getValues());
            $table->unsignedInteger('listing_id');
            $table->unsignedInteger('user_id');
            $table->foreign('listing_id')->references('id')->on('listings');
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
        Schema::dropIfExists('exchange_spaces');
    }
}
