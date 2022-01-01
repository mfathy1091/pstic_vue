<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('referral_source_id');
            $table->date('referral_date')->nullable();
            $table->date('close_date')->nullable();
            $table->string('referring_person_name');
            $table->string('referring_person_email');
            $table->text('referral_narrative_reason');
            $table->unsignedBigInteger('casee_id')->nullable();

            $table->unsignedBigInteger('current_status_id')->nullable();
            $table->unsignedBigInteger('current_assigned_psw_id')->nullable();
            $table->timestamps();

            $table->foreign('casee_id')->references('id')->on('casees')->onDelete('cascade');
            $table->foreign('referral_source_id')->references('id')->on('referral_sources')->onDelete('cascade');
            $table->foreign('current_status_id')->references('id')->on('statuses')->onDelete('cascade');
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
        Schema::dropIfExists('referrals');
    }
}
