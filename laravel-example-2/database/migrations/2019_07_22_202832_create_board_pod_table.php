<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardPodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_pod', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pod_id')->nullable();
            $table->foreign('pod_id')->references('id')->on('pods');
            $table->unsignedBigInteger('board_id')->nullable();
            $table->foreign('board_id')->references('id')->on('boards');
            $table->unsignedInteger('sort_order')->nullable();
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
        Schema::dropIfExists('board_pod');
    }
}
