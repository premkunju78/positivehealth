<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nette\Schema\Schema as SchemaSchema;

class CreateAlliancePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('alliance_partners', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index('group_users_user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->string('company_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('company_contact')->nullable();
            $table->string('website_name')->nullable();
            $table->string('url')->nullable();
            $table->string('group')->nullable();
            $table->string('type')->nullable();
            $table->text('welcome_message')->nullable();
            $table->longText('address')->nullable(); 
            $table->string('city', 100)->nullable(); 
            $table->string('pincode', 100)->nullable(); 
            $table->string('state', 100)->nullable(); 
            $table->string('logo')->nullable();
            $table->string('signature_img')->nullable();
            $table->string('banner_img')->nullable();
            $table->timestamps();
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alliance_partners');
    }
}
