<?php

use App\Support\HasOccupations;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfessionalInformationsTable extends Migration
{
    use HasOccupations;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_professional_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->enum('occupation', $this->getUserOccupations())->nullable();
            $table->string('occupation_other')->nullable();
            $table->string('years_of_experience')->nullable();
            $table->string('company_name')->nullable();
            $table->text('links')->nullable();
            $table->text('professional_background')->nullable();
            $table->text('areas_served')->nullable();
            $table->boolean('ibba_designation')->nullable();
            $table->boolean('cbi_designation')->nullable();
            $table->boolean('m_a_source_designation')->nullable();
            $table->boolean('m_ami_designation')->nullable();
            $table->boolean('am_aa_designation')->nullable();
            $table->boolean('abba_designation')->nullable();
            $table->boolean('abi_designation')->nullable();
            $table->text('other_designations')->nullable();
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
        Schema::dropIfExists('user_professional_informations');
    }
}
