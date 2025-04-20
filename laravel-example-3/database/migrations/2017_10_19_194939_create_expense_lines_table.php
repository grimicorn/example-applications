<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expense_id')->unsigned();
            $table->integer('amount')->unsigned();
            $table->date('year');
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
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
        Schema::dropIfExists('expense_lines');
    }
}
