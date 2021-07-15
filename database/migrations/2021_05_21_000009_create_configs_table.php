<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->unique();
            $table->string('details')->nullable();
            $table->string('status');
            $table->string('youtube_link')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
