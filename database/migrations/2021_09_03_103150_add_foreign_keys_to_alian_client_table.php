<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAlianClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alian_client', function (Blueprint $table) {
          $table->foreign('aliance_id', 'alian_client_aliance_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
          $table->foreign('client_id', 'alian_client_client_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alian_client', function (Blueprint $table) {
            //
        });
    }
}
