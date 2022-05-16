<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsIntakeStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ps_intake_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ps_intake_id');
            $table->unsignedBigInteger('status_id');
            $table->boolean('is_new');
            $table->date('month')->nullable();

            $table->foreign('ps_intake_id')->references('id')->on('ps_intakes')->onDelete('cascade');
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
        Schema::dropIfExists('ps_intake_statuses');
    }
}
