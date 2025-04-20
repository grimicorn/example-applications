<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// phpcs:ignore
class CreateSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snapshots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('snapshot_configuration_id');
            $table->boolean('is_baseline')->default(false);
            $table->float('difference', 9, 6)->nullable();
            $table->timestamps();
            $table->foreign('snapshot_configuration_id')->references('id')->on('site_pages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snapshots');
    }
}
