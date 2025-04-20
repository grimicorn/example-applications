<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->string('slug');
            $table->integer('parent_id')->unsigned()->default(0);
            $table->unique(['label', 'slug', 'parent_id']);
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
        Schema::dropIfExists('business_categories');
    }
}
