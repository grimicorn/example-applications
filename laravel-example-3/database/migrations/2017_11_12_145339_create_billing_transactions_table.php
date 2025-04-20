<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Support\User\BillingTransactionType;
use Illuminate\Database\Migrations\Migration;

class CreateBillingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->text('label')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount')->nullable();
            $table->enum('type', BillingTransactionType::getValues())->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_transactions');
    }
}
