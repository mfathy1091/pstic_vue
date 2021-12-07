<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePssCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pss_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('direct_beneficiary_id');
            $table->unsignedBigInteger('current_status_id')->nullable();
            $table->unsignedBigInteger('assigned_psw_id');
            $table->timestamps();

            $table->foreign('direct_beneficiary_id')->references('id')->on('beneficiaries')->onDelete('cascade');
            $table->foreign('current_status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('assigned_psw_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pss_cases');
    }
}
