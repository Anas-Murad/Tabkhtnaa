<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_addresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;


            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('user_addresses')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('cascade')
                ->nullOnDelete() ;





            $table->string('name')->nullable();
            $table->string('place')->nullable();

            $table->string('neighborhood')->nullable();
            $table->string('build_address')->nullable();
            $table->string('floor')->nullable();
            $table->string('apartment_address')->nullable();
            $table->string('details')->nullable();

            $table->string('latitude' )->nullable();
            $table->string('longitude' )->nullable();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_addresses');
    }
};
