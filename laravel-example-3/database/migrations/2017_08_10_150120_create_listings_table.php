<?php

use App\Support\HasStates;
use App\Support\HasListingTypes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    use HasListingTypes, HasStates;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->enum('type', $this->getListingTypes())->nullable();
            $table->text('title')->nullable();
            $table->text('business_name')->nullable();
            $table->double('asking_price', 15, 2)->nullable();
            $table->boolean('name_visible')->nullable();
            $table->text('summary_business_description')->nullable();
            $table->text('business_description')->nullable();
            $table->integer('business_category_id')->unsigned()->nullable();
            $table->integer('business_sub_category_id')->unsigned()->nullable();
            $table->string('year_established', 4)->nullable();
            $table->integer('number_of_employees')->nullable();
            $table->double('revenue', 15, 2)->nullable();
            $table->double('discretionary_cash_flow', 15, 2)->nullable();
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->text('city')->nullable();
            $table->enum('state', array_keys($this->getStates()))->nullable();
            $table->string('zip_code')->nullable();
            $table->boolean('address_visible')->nullable();
            $table->text('location_description')->nullable();
            $table->double('pre_tax_earnings', 15, 2)->nullable();
            $table->double('ebitda', 15, 2)->nullable();
            $table->boolean('real_estate_included')->nullable();
            $table->double('real_estate_estimated', 15, 2)->nullable();
            $table->boolean('fixtures_equipment_included')->nullable();
            $table->double('fixtures_equipment_estimated', 15, 2)->nullable();
            $table->boolean('inventory_included')->nullable();
            $table->double('inventory_estimated', 15, 2)->nullable();
            $table->text('business_overview')->nullable();
            $table->text('products_services')->nullable();
            $table->text('market_overview')->nullable();
            $table->text('competitive_position')->nullable();
            $table->text('business_performance_outlook')->nullable();
            $table->boolean('financing_available')->nullable();
            $table->text('financing_available_description')->nullable();
            $table->boolean('support_training')->nullable();
            $table->text('support_training_description')->nullable();
            $table->text('reason_for_selling')->nullable();
            $table->date('desired_sale_date')->nullable();
            $table->boolean('seller_non_compete')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('business_category_id')->references('id')->on('business_categories');
            $table->foreign('business_sub_category_id')->references('id')->on('business_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listings');
    }
}
