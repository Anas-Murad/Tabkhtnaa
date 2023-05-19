<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cart_meal_accessories', function (Blueprint $table) {
            $table->unsignedBigInteger('cart_meal_id');
            $table->unsignedBigInteger('accessory_id');

            $table->foreign('cart_meal_id')->references('id')->on('cart_meals')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('accessory_id')->references('id')->on('accessories')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_meal_accessories');
    }
};
