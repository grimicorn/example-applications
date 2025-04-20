<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnapshotConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snapshot_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('site_page_id');
            $table->unsignedInteger('width');
            $table->boolean('needs_review')->default(false);
            $table->timestamps();
            $table->foreign('site_page_id')->references('id')->on('site_pages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snapshot_configurations');
    }
}
