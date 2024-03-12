<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); 
            $table->text('description'); 
            $table->string('type');
            $table->json('plans')->nullable();
            $table->string('image');
            $table->string('payment_type');
            $table->string('communication_type');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('sessions')->nullable();
            $table->integer('creator');
            $table->foreign('creator')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
        Schema::dropIfExists('packages');
    }
}
