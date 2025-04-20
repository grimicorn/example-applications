<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\JobType;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->foreign('machine_id')->references('id')->on('machines');
            $table->string('uuid');
            $table->string('customer_name')->nullable();
            $table->string('work_order_number')->index()->nullable();
            $table->string('control_number_1')->index()->nullable();
            $table->unsignedInteger('screens_1')->nullable();
            $table->string('placement_1')->nullable();
            $table->string('imported_placement_1')->nullable();
            $table->string('control_number_2')->index()->nullable();
            $table->unsignedInteger('screens_2')->nullable();
            $table->string('placement_2')->nullable();
            $table->string('imported_placement_2')->nullable();
            $table->string('control_number_3')->index()->nullable();
            $table->unsignedInteger('screens_3')->nullable();
            $table->string('placement_3')->nullable();
            $table->string('imported_placement_3')->nullable();
            $table->string('control_number_4')->index()->nullable();
            $table->unsignedInteger('screens_4')->nullable();
            $table->string('placement_4')->nullable();
            $table->string('imported_placement_4')->nullable();
            $table->string('product_location_wc')->nullable();
            $table->string('wip_status')->index()->nullable();
            $table->string('sku_number')->nullable();
            $table->string('art_status')->nullable();
            $table->unsignedInteger('priority')->nullable();
            $table->string('pick_status')->nullable();
            $table->unsignedInteger('total_quantity')->nullable();
            $table->unsignedInteger('small_quantity')->nullable();
            $table->unsignedInteger('medium_quantity')->nullable();
            $table->unsignedInteger('large_quantity')->nullable();
            $table->unsignedInteger('xlarge_quantity')->nullable();
            $table->unsignedInteger('2xlarge_quantity')->nullable();
            $table->unsignedInteger('other_quantity')->nullable();
            $table->unsignedInteger('complete_count')->nullable();
            $table->unsignedInteger('issue_count')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('type')->default(JobType::DEFAULT);
            $table->unsignedInteger('duration')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('garment_ready')->default(false);
            $table->boolean('screens_ready')->default(false);
            $table->boolean('ink_ready')->default(false);
            $table->integer('flag')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
