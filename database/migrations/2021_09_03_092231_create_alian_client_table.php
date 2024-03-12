<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlianClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alian_client', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('aliance_id')->index('alian_client_aliance_id');
            $table->integer('client_id')->index('alian_client_client_id');
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
        Schema::dropIfExists('alian_client');
    }
}
