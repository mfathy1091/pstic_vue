<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatebeneficiariesCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiaries_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('beneficiary_id');
            $table->unsignedInteger('pss_case_id');
            $table->timestamps();



            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->onDelete('cascade');
            $table->foreign('pss_case_id')->references('id')->on('pss_cases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beneficiaries_cases');
    }
}
