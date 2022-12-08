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


        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique() ->nullable();
            $table->string('residence_country_id') ->nullable();
            $table->string('country_code') ->nullable();
            $table->string('mobile') ->nullable();
            $table->string('username') ->nullable();
            $table->date('dob') ->nullable();
            $table->enum('gender'  ,['male','female','other']);
            $table->enum('source'  ,['facebook','google','apple','normal']) ->default('normal');
            $table->string('udid')->nullable();
            $table->string('def_lang')->default('ar');

            $table->string('profile_image')->nullable();
            $table->string('mobile_verified')->nullable();
            $table->enum('online_status'  ,['online','busy','unavailable','available']) ->default('online');
            $table->enum('type'  ,['client','delivery','chef']);

            $table->enum('account_status'  ,['pending','active','rejected','blocked']);
            $table->string('account_comment')->nullable();


            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('users');
    }
};
