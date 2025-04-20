<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDesiredPurchaseCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_desired_purchase_criterias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->text('locations')->nullable();
            $table->string('asking_price_minimum')->nullable();
            $table->string('asking_price_maximum')->nullable();
            $table->string('revenue_minimum')->nullable();
            $table->string('revenue_maximum')->nullable();
            $table->string('ebitda_minimum')->nullable();
            $table->string('ebitda_maximum')->nullable();
            $table->string('pre_tax_income_minimum')->nullable();
            $table->string('pre_tax_income_maximum')->nullable();
            $table->string('discretionary_cash_flow_minimum')->nullable();
            $table->string('discretionary_cash_flow_maximum')->nullable();
            $table->string('business_categories')->nullable();
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
        Schema::dropIfExists('user_desired_purchase_criterias');
    }
}
