<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('last_name')->nullable();
            //2nd step data
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->integer('start_age')->nullable();
            //3rd step data
            $table->string('tobaco_type')->nullable();
            $table->integer('daily_cigarettes')->nullable();
            $table->integer('weekly_hookah')->nullable();
            //4th step data
            $table->string('marital_status')->nullable();
            $table->string('education')->nullable();
            $table->string('job')->nullable();
            /////////////////////////////////////////////////
            $table->integer('step')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctor_appointments', function (Blueprint $table) {
            //
        });
    }
}
