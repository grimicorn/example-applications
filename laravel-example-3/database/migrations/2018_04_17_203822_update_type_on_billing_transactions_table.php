<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Support\User\BillingTransactionType;
use Illuminate\Database\Migrations\Migration;

class UpdateTypeOnBillingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_transactions', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            $types = collect(BillingTransactionType::getConstants())->map(function ($value) {
                return "'{$value}'";
            })->implode(',');
            DB::statement("ALTER TABLE billing_transactions CHANGE type type ENUM({$types})");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_transactions', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            $types = collect(BillingTransactionType::getConstants())->map(function ($value) {
                return "'{$value}'";
            })->implode(',');
            DB::statement("ALTER TABLE billing_transactions CHANGE type type ENUM({$types})");
        });
    }
}
