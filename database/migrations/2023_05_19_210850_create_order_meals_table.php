<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_meals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')
                ->nullOnDelete() ;


            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;


            $table->unsignedBigInteger('meal_id')->nullable();
            $table->foreign('meal_id')->references('id')->on('meals')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->string('meal_name')->nullable();
            $table->unsignedSmallInteger('quantity');

            $table->unsignedDecimal('price', 10, 2)->nullable();
            $table->unsignedDecimal('discount', 10, 2)->nullable();
            $table->unsignedDecimal('additions_price', 10, 2)->nullable();
            $table->unsignedDecimal('total', 10, 2)->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_meals');
    }
};
