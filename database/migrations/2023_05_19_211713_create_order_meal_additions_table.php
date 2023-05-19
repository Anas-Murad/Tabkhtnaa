<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_meal_additions', function (Blueprint $table) {
            $table->unsignedBigInteger('order_meal_id');
            $table->foreign('order_meal_id')
                ->references('id')
                ->on('order_meals')
                ->onUpdate('cascade')
                ->onDelete('cascade');



            $table->unsignedBigInteger('addition_id');
            $table->foreign('addition_id')->references('id')->on('additions')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_meal_additions');
    }
};
