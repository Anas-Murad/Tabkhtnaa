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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('file');
            $table->enum('type' ,[
                'front_id_image', //صورة الهوية الامامية
                'background_id_photo', //صورة الهوية الخلفية
                'no_criminal_record', //عدم محكومية
                'leave_diseases', //خلوا امراض
                'practicing_profession', //مزاولة المهنة
                'stool_examination', //فحص براز
                'urine_test', //فحص بول
                'blood_test' , //فحص دم
                'driving_license' , //رخصة قيادة
                'car_trunk_image' , //صورة حقيبة السيارة
            ]);
            $table->enum('status' , ['approved','reject','expired','incorrect_data','pending'])->default('pending');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('documents');
    }
};
