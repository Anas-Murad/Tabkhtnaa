<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_meal_accessories', function (Blueprint $table) {
            $table->unsignedBigInteger('order_meal_id');
            $table->unsignedBigInteger('accessory_id')->nullable();

            $table->foreign('order_meal_id')
                ->references('id')
                ->on('order_meals')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('accessory_id')
                ->references('id')
                ->on('accessories')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_meal_accessories');
    }
};
