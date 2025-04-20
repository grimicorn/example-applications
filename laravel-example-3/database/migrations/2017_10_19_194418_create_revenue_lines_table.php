<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevenueLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenue_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('revenue_id')->unsigned();
            $table->integer('amount')->unsigned();
            $table->date('year');
            $table->foreign('revenue_id')->references('id')->on('revenues')->onDelete('cascade');
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
        Schema::dropIfExists('revenue_lines');
    }
}
