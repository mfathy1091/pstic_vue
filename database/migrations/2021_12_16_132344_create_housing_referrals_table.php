<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousingReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('housing_referrals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('casee_id')->nullable();
            $table->unsignedBigInteger('referral_source_id');
            $table->date('referral_date')->nullable();
            $table->string('referring_person_name');
            $table->string('referring_person_email');
            $table->text('referral_narrative_reason');
            $table->unsignedBigInteger('grant_status_id');
            $table->unsignedBigInteger('grant_amount');
            $table->unsignedBigInteger('assigned_housing_advocate_id')->nullable();
            $table->timestamps();

            $table->foreign('casee_id')->references('id')->on('casees')->onDelete('cascade');
            $table->foreign('referral_source_id')->references('id')->on('referral_sources')->onDelete('cascade');
            $table->foreign('grant_status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('assigned_housing_advocate_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('housing_referrals');
    }
}
