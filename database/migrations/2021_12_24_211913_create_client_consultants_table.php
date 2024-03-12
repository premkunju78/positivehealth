<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientConsultantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_consultants', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->foreign('client_id')->references('id')->on('users');
            $table->integer('consultant_id')->nullable();
            $table->foreign('consultant_id')->references('id')->on('users');
            $table->tinyInteger('referred')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_consultants');
    }
}
