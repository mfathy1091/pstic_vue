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
            $table->unsignedInteger('beneficiary_id');
            $table->unsignedInteger('service_type_id');
            $table->timestamps();

            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->onDelete('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');

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
