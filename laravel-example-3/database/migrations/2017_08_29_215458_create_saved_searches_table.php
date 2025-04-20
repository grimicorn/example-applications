<?php

use App\Support\HasStates;
use App\Support\HasListingTypes;
use Illuminate\Support\Facades\Schema;
use App\Support\Listing\HasSearchOptions;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedSearchesTable extends Migration
{
    use HasListingTypes, HasStates, HasSearchOptions;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_searches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->enum('transaction_type', $this->transactionTypes())->nullable();
            $table->enum('listing_updated_at', $this->listingUpdatedAt())->nullable();
            $table->string('business_categories')->nullable();
            $table->string('keyword')->nullable();
            $table->integer('zip_code_radius')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('city')->nullable();
            $table->enum('state', array_keys($this->getStates()))->nullable();
            $table->boolean('es_documents')->default(false);
            $table->boolean('es_business_plan')->default(false);
            $table->boolean('es_historical_financials')->default(false);
            $table->double('asking_price_min', 15, 2)->nullable();
            $table->double('asking_price_max', 15, 2)->nullable();
            $table->double('cash_flow_min', 15, 2)->nullable();
            $table->double('cash_flow_max', 15, 2)->nullable();
            $table->double('pre_tax_income_min', 15, 2)->nullable();
            $table->double('pre_tax_income_max', 15, 2)->nullable();
            $table->double('ebitda_min', 15, 2)->nullable();
            $table->double('ebitda_max', 15, 2)->nullable();
            $table->double('revenue_min', 15, 2)->nullable();
            $table->double('revenue_max', 15, 2)->nullable();
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
        Schema::dropIfExists('saved_searches');
    }
}
