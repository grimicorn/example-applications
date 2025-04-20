<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// phpcs:ignore
class CreateSitePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('site_id');
            $table->text('url');
            $table->boolean('ignored')->default(false);
            $table->boolean('needs_review')->default(false);
            $table->float('difference_threshold')->nullable();
            $table->timestamp('processing')->nullable();
            $table->timestamps();
            $table->foreign('site_id')->references('id')->on('sites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_pages');
    }
}
