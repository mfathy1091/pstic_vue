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
            $table->unsignedBigInteger('beneficiary_id');
            // $table->unsignedBigInteger('ps_intake_beneficiary_id');
            $table->unsignedBigInteger('ps_intake_id');
            $table->date('activity_date')->nullable();
            $table->text('comment');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_emergency');
            $table->timestamps();

            // foreign keys
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->onDelete('cascade');
            // $table->foreign('ps_intake_beneficiary_id')->references('id')->on('ps_intake_beneficiaries')->onDelete('cascade');
            $table->foreign('ps_intake_id')->references('id')->on('ps_intakes')->onDelete('cascade');
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
