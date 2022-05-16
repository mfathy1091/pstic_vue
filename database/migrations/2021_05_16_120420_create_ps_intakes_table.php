<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsIntakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ps_intakes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referral_source_id')->nullable();
            $table->date('referral_date');
            $table->date('close_date')->nullable();
            // $table->unsignedBigInteger('elapsed_months_since_intake');
            $table->string('referring_person_name')->nullable();
            $table->string('referring_person_email')->nullable();
            $table->text('referral_narrative_reason')->nullable();
            $table->unsignedBigInteger('casee_id')->nullable();

            $table->unsignedBigInteger('current_assigned_psw_id')->nullable();
            // $table->unsignedBigInteger('budget_id')->nullable();
            
            $table->timestamps();

            $table->foreign('casee_id')->references('id')->on('casees')->onDelete('cascade');
            $table->foreign('referral_source_id')->references('id')->on('referral_sources')->onDelete('cascade');
            $table->foreign('current_assigned_psw_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ps_intakes');
    }
}
