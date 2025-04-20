<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachinePodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_pod', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pod_id')->nullable();
            $table->foreign('pod_id')->references('id')->on('pods');
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->foreign('machine_id')->references('id')->on('machines');
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
        Schema::dropIfExists('machine_pod');
    }
}
