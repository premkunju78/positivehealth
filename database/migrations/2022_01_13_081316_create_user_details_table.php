<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('logo')->nullable();
            $table->string('signature_img')->nullable();
            $table->string('banner_img')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender', 10)->nullable(); 
            $table->string('occupation', 191)->nullable(); 
            $table->string('guard_mob', 20)->nullable(); 
            $table->longText('address')->nullable(); 
            $table->string('city', 100)->nullable(); 
            $table->string('pincode', 100)->nullable(); 
            $table->string('state', 100)->nullable(); 
            $table->string('company_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('company_contact')->nullable();
            $table->boolean('league')->default(0);
            $table->string('clinic_name')->nullable();
            $table->string('website_name')->nullable();
            $table->string('url')->nullable();
            $table->string('group')->nullable();
            $table->string('type')->nullable();
            $table->text('welcome_message')->nullable();
            $table->integer('cost')->nullable();
            $table->integer('cost_percent')->nullable();
            $table->string('specialization')->nullable(); 
            $table->string('authorization')->nullable();
            $table->string('specialized_in')->nullable();
            $table->string('qualification')->nullable();
            $table->json('certificates')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('cancelled_cheque')->nullable();
            $table->boolean('is_upgraded')->default(0);
            $table->text('bio')->nullable();
            $table->string('experience')->nullable();
            $table->text('aim')->nullable();
            $table->json('languages')-> nullable();
            $table->json('skills')-> nullable();
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
        Schema::dropIfExists('user_details');
    }
}
