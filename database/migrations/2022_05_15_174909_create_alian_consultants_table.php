<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlianConsultantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alian_consultants', function (Blueprint $table) {
            $table->id();
            $table->integer('aliance_id')->index('alian_client_aliance_id');
            $table->integer('consultant_id')->index('alian_client_consultant_id');
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
        Schema::dropIfExists('alian_consultants');
    }
}
