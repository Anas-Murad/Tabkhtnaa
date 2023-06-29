<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cart_meals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('meal_id');
            $table->unsignedSmallInteger('quantity');
            $table->text('note')->nullable();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('cart_id')->references('id')->on('carts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('meal_id')->references('id')->on('meals')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_meals');
    }
};
