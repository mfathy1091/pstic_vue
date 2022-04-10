<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('beneficiary_id');
            $table->unsignedInteger('ps_intake_beneficairy_id');
            $table->unsignedInteger('ps_intake_id');
            $table->date('emergency_date');
            $table->text('comment');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            // $table->unsignedInteger('record_id');
            // $table->unsignedInteger('casee_id');

            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->onDelete('cascade');
            $table->foreign('ps_intake_beneficairy_id')->references('id')->on('ps_intakes_beneficairies')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ps_intake_id')->references('id')->on('ps_intakes')->onDelete('cascade');
            //$table->foreign('record_id')->references('id')->on('records')->onDelete('cascade');
            // $table->foreign('casee_id')->references('id')->on('casees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emergencies');
    }
}
