<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidedServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provided_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('activity_id');
            $table->unsignedBigInteger('referral_beneficiary_id');
            $table->unsignedInteger('service_type_id');
            $table->date('provision_date')->nullable();
            $table->unsignedInteger('user_id');
            $table->timestamps();            

            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('referral_beneficiary_id')->references('id')->on('referrals_beneficiaries')->onDelete('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');
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
        Schema::dropIfExists('provided_services');
    }
}
