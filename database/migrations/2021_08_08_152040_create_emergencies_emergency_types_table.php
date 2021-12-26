<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergenciesEmergencyTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergencies_emergency_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('emergency_id');
            $table->unsignedInteger('emergency_type_id');
            $table->timestamps();

            $table->foreign('emergency_id')->references('id')->on('emergencies')->onDelete('cascade');
            $table->foreign('emergency_type_id')->references('id')->on('emergency_types')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emergencies_emergency_types');
    }
}
