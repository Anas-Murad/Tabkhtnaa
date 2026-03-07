<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique() ->nullable();
            $table->string('residence_country_id') ->nullable();
            $table->string('country_code') ->nullable();
            $table->string('mobile') ->nullable();
            $table->date('dob') ->nullable();
            $table->enum('gender'  ,['male','female','other'])->nullable();
            $table->string('def_lang')->default('ar');
            $table->string('profile_image')->nullable();
            $table->enum('online_status'  ,['online','offline']) ->default('offline');
            $table->enum('account_status'  ,['active','inactive'])->default('active');
            $table->string('password')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('admins');
    }
};
