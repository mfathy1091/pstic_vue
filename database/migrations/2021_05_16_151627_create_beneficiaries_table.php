<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatebeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('casee_id');
            $table->string('name');
            $table->string('passport_number')->nullable()->unique();
            $table->integer('age');
            $table->unsignedbigInteger('gender_id');
            $table->unsignedbigInteger('nationality_id');
            $table->string('phone_number')->nullable();
            $table->unsignedInteger('is_active')->default(1);

            $table->boolean('is_registered')->nullable();
            $table->unsignedbigInteger('file_individual_number')->nullable();
            $table->unsignedbigInteger('relationship_id')->nullable();
            $table->timestamps();

            // foreign keys
            $table->foreign('casee_id')->references('id')->on('casees')->onDelete('cascade');
            $table->foreign('gender_id')->references('id')->on('genders')->onDelete('cascade');
            $table->foreign('nationality_id')->references('id')->on('nationalities')->onDelete('cascade');
            $table->foreign('relationship_id')->references('id')->on('relationships')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beneficiaries');
    }
}
