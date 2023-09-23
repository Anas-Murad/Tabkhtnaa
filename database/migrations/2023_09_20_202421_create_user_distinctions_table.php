<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_distinctions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;


            $table->date('from_date');
            $table->date('to_date');
            $table->enum('status' ,['new' , 'active' ,'ended']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_distinctions');
    }
};
