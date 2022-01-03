<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referral_id');
            $table->unsignedBigInteger('month_id');
            $table->unsignedBigInteger('elapsed_months_since_intake');
            $table->unsignedBigInteger('status_id');
            $table->boolean('is_new');
            $table->timestamps();

            // foreign keys
            $table->foreign('referral_id')->references('id')->on('referrals')->onDelete('cascade');
            $table->foreign('month_id')->references('id')->on('months')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
