<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;


            $table->string('name' ,100);
            $table->string('place' ,100);


            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('cascade')
                ->nullOnDelete() ;


            $table->string('neighborhood' ,100);
            $table->string('build_address' ,100);
            $table->string('floor' ,100);
            $table->string('apartment_address' ,100);
            $table->text('details');


            $table->string('latitude' )->nullable();
            $table->string('longitude' )->nullable();


            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }

};
