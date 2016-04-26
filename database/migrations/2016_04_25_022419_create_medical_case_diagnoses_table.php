<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalCaseDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_case_diagnoses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('medical_case_id');
            $table->bigInteger('diagnosis_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('medical_case_diagnoses');
    }
}
