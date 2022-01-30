<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('record_id');
            $table->unsignedInteger('referral_id');
            $table->unsignedInteger('casee_id');
            $table->unsignedBigInteger('referral_beneficiary_id');
            $table->date('activity_date')->nullable();
            $table->text('comment');
            $table->unsignedInteger('user_id');
            $table->boolean('is_emergency');
            $table->timestamps();

            // foreign keys
            $table->foreign('record_id')->references('id')->on('records')->onDelete('cascade');
            $table->foreign('referral_beneficiary_id')->references('id')->on('referrals_beneficiaries')->onDelete('cascade');
            $table->foreign('referral_id')->references('id')->on('referrals')->onDelete('cascade');
            $table->foreign('casee_id')->references('id')->on('casees')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
