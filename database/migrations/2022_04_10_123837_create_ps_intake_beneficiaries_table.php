<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsIntakeBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ps_intake_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ps_intake_id');
            $table->unsignedBigInteger('beneficiary_id');
            $table->boolean('is_direct')->default(0);
            $table->timestamps();

            $table->foreign('ps_intake_id')->references('id')->on('ps_intakes')->onDelete('cascade');
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ps_intake_beneficiaries');
    }
}
