<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('doctor_appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('date_time')->nullable();
            $table->string('zoom_id')->nullable();
            $table->integer('doctor_id')->unsigned()->nullable();
            $table->integer('patient_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
